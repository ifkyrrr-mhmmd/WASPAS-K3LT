<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use Exception;

class WaspasCalculatorService
{
    /**
     * Calculate WASPAS algorithm for a specific user's data.
     * Lambda dikunci pada 0.5 sesuai spesifikasi.
     * 
     * @param int $userId
     * @param float $lambda (default 0.5, dikunci statis)
     * @return array
     */
    public function calculate($userId, float $lambda = 0.5): array
    {
        $criteria = Criteria::where('user_id', $userId)->orderBy('id')->get();
        $alternatives = Alternative::with('scores')->where('user_id', $userId)->orderBy('id')->get();

        if ($criteria->isEmpty() || $alternatives->isEmpty()) {
            throw new Exception('Data kriteria atau alternatif belum lengkap untuk melakukan perhitungan.');
        }

        // Konversi ke format array untuk reuse method generik
        $criteriaArray = $criteria->map(function ($c) {
            return [
                'id'     => $c->id,
                'name'   => $c->name,
                'type'   => $c->type,
                'weight' => (float) $c->weight,
            ];
        })->toArray();

        $alternativesArray = $alternatives->map(function ($alt) use ($criteria) {
            $scores = [];
            foreach ($criteria as $c) {
                $scores[$c->id] = $alt->scoreFor($c->id) ?? 0;
            }
            return [
                'id'     => $alt->id,
                'name'   => $alt->name,
                'scores' => $scores,
            ];
        })->toArray();

        $result = $this->calculateWithCustomWeights($criteriaArray, $alternativesArray, $lambda);

        // Sertakan model asli untuk backward compatibility
        $result['criteria'] = $criteria;
        $result['alternatives'] = $alternatives;

        return $result;
    }

    /**
     * Calculate WASPAS dengan data kustom (tanpa query DB).
     * Digunakan oleh Analisis Sensitivitas untuk simulasi bobot.
     *
     * @param array $criteria  Array of ['id', 'name', 'type', 'weight']
     * @param array $alternatives  Array of ['id', 'name', 'scores' => [criteria_id => value]]
     * @param float $lambda
     * @return array
     */
    public function calculateWithCustomWeights(array $criteria, array $alternatives, float $lambda = 0.5): array
    {
        if (empty($criteria) || empty($alternatives)) {
            throw new Exception('Data kriteria atau alternatif belum lengkap untuk melakukan perhitungan.');
        }

        // 1. Dapatkan max dan min untuk setiap kriteria
        $extremes = [];
        foreach ($criteria as $c) {
            $scores = array_map(function ($alt) use ($c) {
                return $alt['scores'][$c['id']] ?? 0;
            }, $alternatives);

            $extremes[$c['id']] = [
                'max' => max($scores),
                'min' => min($scores),
            ];
        }

        // 2. Hitung total bobot dan normalisasikan bobot
        $totalWeight = array_sum(array_column($criteria, 'weight'));
        $normalizedWeights = [];
        foreach ($criteria as $c) {
            $normalizedWeights[$c['id']] = ($totalWeight > 0) ? ($c['weight'] / $totalWeight) : 0;
        }

        $normalizedMatrix = [];
        $rankings = [];

        // 3. Normalisasi dan perhitungan WSM (Q1), WPM (Q2), Qi
        foreach ($alternatives as $alt) {
            $q1 = 0; // Weighted Sum Model (SAW)
            $q2 = 1; // Weighted Product Model

            $normRow = [];

            foreach ($criteria as $c) {
                $rawScore = $alt['scores'][$c['id']] ?? 0;
                $normalizedScore = 0;

                // Normalisasi berdasarkan tipe kriteria
                if ($c['type'] === 'Benefit') {
                    $max = $extremes[$c['id']]['max'];
                    $normalizedScore = ($max > 0) ? ($rawScore / $max) : 0;
                } else {
                    $min = $extremes[$c['id']]['min'];
                    $normalizedScore = ($rawScore > 0) ? ($min / $rawScore) : 0;
                }

                $normRow[$c['id']] = $normalizedScore;

                // Gunakan bobot ter-normalisasi
                $w = $normalizedWeights[$c['id']];

                // Hitung Q1 (SAW)
                $q1 += ($normalizedScore * $w);

                // Hitung Q2 (WP)
                $q2 *= pow($normalizedScore, $w);
            }

            $normalizedMatrix[$alt['id']] = $normRow;

            // Hitung Qi (WASPAS final score)
            $qi = ($lambda * $q1) + ((1 - $lambda) * $q2);

            $rankings[] = [
                'alternative_id'   => $alt['id'],
                'alternative_name' => $alt['name'],
                'q1'               => $q1,
                'q2'               => $q2,
                'qi'               => $qi,
            ];
        }

        // 4. Urutkan berdasarkan Qi tertinggi (Descending)
        usort($rankings, function ($a, $b) {
            return $b['qi'] <=> $a['qi'];
        });

        // Tambahkan ranking (1, 2, 3...)
        foreach ($rankings as $i => &$rank) {
            $rank['rank'] = $i + 1;
        }

        return [
            'extremes'         => $extremes,
            'normalizedMatrix' => $normalizedMatrix,
            'rankings'         => $rankings,
            'lambda'           => $lambda,
        ];
    }
}
