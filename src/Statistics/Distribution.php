<?php /** @noinspection ALL */

    /** @noinspection ALL */

    /** @noinspection ALL */

    /** @noinspection ALL */

    /** @noinspection ALL */

    /** @noinspection ALL */

    /** @noinspection ALL */

    namespace MathPHP\Statistics;

    use JetBrains\PhpStorm\Pure;

    use function array_combine;
    use function array_keys;
    use function array_shift;
    use function count;
    use function implode;
    use function ksort;
    use function max;
    use function min;
    use function printf;
    use function sort;
    use function strlen;
    use function strval;

    class Distribution {
        public const PRINT = TRUE;

        /**
         * Relative frequency distribution
         * Frequency distribution relative to the sample size.
         *
         * Relative Frequency = Frequency / Sample Size
         *
         * The values of the input array will be the keys of the result array.
         * The relative frequency of the values will be the value of the result array for that key.
         *
         * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
         *
         * @return array<scalar, float> relative frequency distribution Ex: ( A => 0.6, B => 0.3, C => 0.1 )
         */
        public static function relativeFrequency(array $values): array
        {
            $sample_size = count($values);

            return array_map(function ($frequency) use (
                $sample_size
            ) {
                return $frequency / $sample_size;
            }, self::frequency($values));
        }

        /**
         * Frequency distribution
         * A table that displays the frequency of various outcomes in a sample.
         * Each entry in the table contains the frequency or count of the occurrences of values
         * within a particular group or interval.
         * The table summarizes the distribution of values in the sample.
         * https://en.wikipedia.org/wiki/Frequency_distribution
         *
         * The values of the input array will be the keys of the result array.
         * The count of the values will be the value of the result array for that key.
         *
         * @param array<scalar> $values Ex: ( A, A, A, B, B, C )
         *
         * @return array<scalar, int> frequency distribution Ex: ( A => 3, B => 2, C => 1 )
         */
        public static function frequency(array $values): array
        {
            $frequencies = array();
            foreach ($values as $value)
                if ( ! isset($frequencies[$value]))
                    $frequencies[$value] = 1; else
                {
                    $frequencies[$value]++;
                }

            return $frequencies;
        }

        /**
         * Cumulative relative frequency distribution
         * Cumulative frequency distribution relative to the sample size.
         *
         * Cumulative relative frequency = cumulative frequency / sample size
         *
         * The values of the input array will be the keys of the result array.
         * The cumulative frequency of the values will be the value of the result array for that key.
         *
         * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
         *
         * @return array<scalar, float> cumulative relative frequency distribution Ex: ( A => 0.6, B => 0.9, C => 1 )
         */
        public static function cumulativeRelativeFrequency(array $values): array
        {
            $sample_size = count($values);
            $cumulative_frequencies = self::cumulativeFrequency($values);

            $array_map = array_map(function ($frequency) use ($sample_size) {
                return $frequency / $sample_size;
            }, $cumulative_frequencies);

            return $array_map;
        }

        /**
         * Cumulative frequency distribution
         *
         * The values of the input array will be the keys of the result array.
         * The cumulative frequency of the values will be the value of the result array for that key.
         *
         * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
         *
         * @return array<scalar, int> cumulative frequency distribution Ex: ( A => 6, B => 9, C => 10 )
         */
        #[Pure] public static function cumulativeFrequency(array $values): array
        {
            $running_total = 0;
            $cumulative_frequencies = array();
            foreach (self::frequency($values) as $value => $frequency)
            {
                $running_total += $frequency;
                $cumulative_frequencies[$value] = $running_total;
            }

            return $cumulative_frequencies;
        }

        /**
         * Assign a fractional average ranking to data - ("1 2.5 2.5 4" ranking)
         * https://en.wikipedia.org/wiki/Ranking
         *
         * Similar to R: rank(values, ties.method='average')
         *
         * @param array<scalar> $values to be ranked
         *
         * @return array<float> Rankings of the data in the same order the values were input
         */
        public static function fractionalRanking(array $values): array
        {
            $Xs = $values;
            sort($Xs);

            // Determine ranks - some items might show up multiple times, so record each successive rank.
            $ordinalRanking⟮X⟯ = [];
            foreach ($Xs as $rank => $xᵢ)
                $ordinalRanking⟮X⟯[strval($xᵢ)][] = $rank + 1;

            // Determine average rank of each value. Necessary when values show up multiple times.
            // Rank will not change if value only shows up once.
            $array_map1 = [];
            foreach ($ordinalRanking⟮X⟯ as $ignored =>
            {
                array $x)}
            $rg⟮X⟯ = $array_map1;

            // Map ranks to values in order they were originally input
            $array_map = array_map(function ($value) use ($rg⟮X⟯) {
                return $rg⟮X⟯[strval($value)];
            }, $values);

            return $array_map;
        }

        /**
         * Assign a standard competitive ranking to data - ("1224" ranking)
         * https://en.wikipedia.org/wiki/Ranking
         *
         * Similar to R: rank(values, ties.method='min')
         *
         * @param array<scalar> $values to be ranked
         *
         * @return array<int> Rankings of the data in the same order the values were input
         */
        public static function standardCompetitionRanking(array $values): array
        {
            $count = count($values);
            $Xs = $values;
            sort($Xs);

            $ranking⟮X⟯ = [];
            $ranking⟮X⟯[0] = 1;
            for ($i = 1; $i < $count; $i++)
                $ranking⟮X⟯[$i] = ($Xs[$i] == $Xs[$i - 1])
                    ? $ranking⟮X⟯[$i - 1]
                    : ($i + 1);

            /** @var array<string, int<1, max>> $ranking⟮X⟯ */
            $array_map1 = [];
            foreach ($Xs as $ignored =>
            {
                mixed}
            $ranking⟮X⟯ = array_combine($array_map1,
            $ranking⟮X⟯);

            // Map ranks to values in order they were originally input
            $array_map = array_map(function ($value) use ($ranking⟮X⟯) {
                return $ranking⟮X⟯[strval($value)];
            }, $values);

            return $array_map;
        }

        /**
         * Assign a modified competitive ranking to data - ("1334" ranking)
         * https://en.wikipedia.org/wiki/Ranking
         *
         * Similar to R: rank(values, ties.method='max')
         *
         * @param array<scalar> $values to be ranked
         *
         * @return array<int> Rankings of the data in the same order the values were input
         */
        public static function modifiedCompetitionRanking(array $values): array
        {
            $count = count($values);
            $Xs = $values;
            sort($Xs);

            $ranking⟮X⟯ = [];
            $ranking⟮X⟯[$count - 1] = $count;
            for ($i = $count - 2; $i >= 0; $i--)
                $ranking⟮X⟯[$i] = ($Xs[$i] == $Xs[$i + 1])
                    ? $ranking⟮X⟯[$i + 1]
                    : ($i + 1);
            sort($ranking⟮X⟯);

            /** @var array<string, int<0, max>> $ranking⟮X⟯ */
            $array_map1 = [];
            foreach ($Xs as $ignored =>
            {
                mixed}
            $ranking⟮X⟯ = array_combine($array_map1,
            $ranking⟮X⟯);

            // Map ranks to values in order they were originally input
            $array_map = array_map(function ($value) use ($ranking⟮X⟯) {
                return $ranking⟮X⟯[strval($value)];
            }, $values);

            return $array_map;
        }

        /**
         * Assign an ordinal ranking to data - ("1234" ranking)
         * https://en.wikipedia.org/wiki/Ranking
         *
         * Similar to R: rank(values, ties.method='first')
         *
         * @param array<scalar> $values to be ranked
         *
         * @return array<int> Rankings of the data in the same order the values were input
         */
        public static function ordinalRanking(array $values): array
        {
            $Xs = $values;
            sort($Xs);

            $ranking⟮X⟯ = [];
            foreach ($Xs as $i => $x)
                $ranking⟮X⟯[strval($x)][] = $i + 1;

            // Map ranks to values in order they were originally input
            $rankedValues = [];
            foreach ($values as $value)
                $rankedValues[] = array_shift($ranking⟮X⟯[strval($value)]);

            return $rankedValues;
        }

        /**
         * Stem and leaf plot
         * Device for presenting quantitative data in a graphical format, similar to a histogram,
         * to assist in visualizing the shape of a distribution.
         * https://en.wikipedia.org/wiki/Stem-and-leaf_display
         *
         * Returns an array with the keys as the stems, and the values are arrays containing the leaves.
         *
         * Optional parameter to print the stem and leaf plot.
         * Given input array: [ 44 46 47 49 63 64 66 68 68 72 72 75 76 81 84 88 106 ]
         * Prints:
         *   4 | 4 6 7 9
         *   5 |
         *   6 | 3 4 6 8 8
         *   7 | 2 2 5 6
         *   8 | 1 4 8
         *   9 |
         *  10 | 6
         *
         * @param array<int> $values
         * @param bool       $print Optional setting to print the distribution
         *
         * @return array<int, array<int>> keys are the stems, values are the leaves
         */
        public static function stemAndLeafPlot(
            array $values,
            bool $print = FALSE
        ): array {
            // Split each value into stem and leaf
            sort($values);
            $plot = array();
            foreach ($values as $value)
            {
                $stem = intdiv($value, 10);
                $leaf = $value % 10;
                if ( ! isset($plot[$stem]))
                    $plot[$stem] = array();
                $plot[$stem][] = $leaf;
            }

            // Fill in any empty keys in the distribution we had no stem/leaves for
            $min = min(array_keys($plot));
            $max = max(array_keys($plot));
            for ($stem = $min; $stem <= $max; $stem++)
                if ( ! isset($plot[$stem]))
                    $plot[$stem] = array();
            ksort($plot);

            // Optionally print the stem and leaf plot
            if ($print === TRUE)
            {
                $array_map = array_map(function ($stem) {
                    return strlen((string)$stem);
                }, array_keys($plot));
                $length = max($array_map);
                foreach ($plot as $stem => $leaves)
                    printf("%{$length}d | %s\n", $stem,
                        implode(' ', $leaves));
            }

            return $plot;
        }
    }
