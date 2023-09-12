<?php

    namespace MathPHP\Probability\Distribution\Table;

    use MathPHP\Exception;

    use function array_key_exists;
    use function sprintf;

    /**
     * Standard normal tables for Z and related methods
     *
     * A standard normal table, also called the unit normal table or Z table,
     * is a mathematical table for the values of Φ, which are the values of the
     * cumulative distribution function of the normal distribution.
     *
     * It is used to find the probability that a statistic is observed below,
     * above, or between values on the standard normal distribution, and by extension,
     * any normal distribution.
     *
     * Since probability tables cannot be printed for every normal distribution,
     * as there are an infinite variety of normal distributions,
     * it is common practice to convert a normal to a standard normal
     * and then use the standard normal table to find probabilities.
     *
     * https://en.wikipedia.org/wiki/Standard_normal_table
     *
     * This table is provided only for completeness. It is common for statistics
     * textbooks to include this table, so this library does as well. It is better
     * to use the standard normal distribution CDF function when a Z score is required.
     */
    class StandardNormal {
        /**
         * Z scores table - cumulative from mean
         * Contains positive and negative Z scores.
         * Negative z-score - value is to the left of the mean.
         * Positive z-score - value is to the right of the mean.
         *
         * @var array<numeric|string, array<int<0, 9>, float>>
         */
        public final const Z_SCORES
            = [
                '-3.4' => [
                    9 => 0.0002,
                    8 => 0.0003,
                    7 => 0.0003,
                    6 => 0.0003,
                    5 => 0.0003,
                    4 => 0.0003,
                    3 => 0.0003,
                    2 => 0.0003,
                    1 => 0.0003,
                    0 => 0.0003,
                ],
                '-3.3' => [
                    9 => 0.0003,
                    8 => 0.0004,
                    7 => 0.0004,
                    6 => 0.0004,
                    5 => 0.0004,
                    4 => 0.0004,
                    3 => 0.0004,
                    2 => 0.0005,
                    1 => 0.0005,
                    0 => 0.0005,
                ],
                '-3.2' => [
                    9 => 0.0005,
                    8 => 0.0005,
                    7 => 0.0005,
                    6 => 0.0006,
                    5 => 0.0006,
                    4 => 0.0006,
                    3 => 0.0006,
                    2 => 0.0006,
                    1 => 0.0007,
                    0 => 0.0007,
                ],
                '-3.1' => [
                    9 => 0.0007,
                    8 => 0.0007,
                    7 => 0.0008,
                    6 => 0.0008,
                    5 => 0.0008,
                    4 => 0.0008,
                    3 => 0.0009,
                    2 => 0.0009,
                    1 => 0.0009,
                    0 => 0.0010,
                ],
                '-3.0' => [
                    9 => 0.0010,
                    8 => 0.0010,
                    7 => 0.0011,
                    6 => 0.0011,
                    5 => 0.0011,
                    4 => 0.0012,
                    3 => 0.0012,
                    2 => 0.0013,
                    1 => 0.0013,
                    0 => 0.0013,
                ],
                '-2.9' => [
                    9 => 0.0014,
                    8 => 0.0014,
                    7 => 0.0015,
                    6 => 0.0015,
                    5 => 0.0016,
                    4 => 0.0016,
                    3 => 0.0017,
                    2 => 0.0018,
                    1 => 0.0018,
                    0 => 0.0019,
                ],
                '-2.8' => [
                    9 => 0.0019,
                    8 => 0.0020,
                    7 => 0.0021,
                    6 => 0.0021,
                    5 => 0.0022,
                    4 => 0.0023,
                    3 => 0.0023,
                    2 => 0.0024,
                    1 => 0.0025,
                    0 => 0.0026,
                ],
                '-2.7' => [
                    9 => 0.0026,
                    8 => 0.0027,
                    7 => 0.0028,
                    6 => 0.0029,
                    5 => 0.0030,
                    4 => 0.0031,
                    3 => 0.0032,
                    2 => 0.0033,
                    1 => 0.0034,
                    0 => 0.0035,
                ],
                '-2.6' => [
                    9 => 0.0036,
                    8 => 0.0037,
                    7 => 0.0038,
                    6 => 0.0039,
                    5 => 0.0040,
                    4 => 0.0041,
                    3 => 0.0043,
                    2 => 0.0044,
                    1 => 0.0045,
                    0 => 0.0047,
                ],
                '-2.5' => [
                    9 => 0.0048,
                    8 => 0.0049,
                    7 => 0.0051,
                    6 => 0.0052,
                    5 => 0.0054,
                    4 => 0.0055,
                    3 => 0.0057,
                    2 => 0.0059,
                    1 => 0.0060,
                    0 => 0.0062,
                ],
                '-2.4' => [
                    9 => 0.0064,
                    8 => 0.0066,
                    7 => 0.0068,
                    6 => 0.0069,
                    5 => 0.0071,
                    4 => 0.0073,
                    3 => 0.0075,
                    2 => 0.0078,
                    1 => 0.0080,
                    0 => 0.0082,
                ],
                '-2.3' => [
                    9 => 0.0084,
                    8 => 0.0087,
                    7 => 0.0089,
                    6 => 0.0091,
                    5 => 0.0094,
                    4 => 0.0096,
                    3 => 0.0099,
                    2 => 0.0102,
                    1 => 0.0104,
                    0 => 0.0107,
                ],
                '-2.2' => [
                    9 => 0.0110,
                    8 => 0.0113,
                    7 => 0.0116,
                    6 => 0.0119,
                    5 => 0.0122,
                    4 => 0.0125,
                    3 => 0.0129,
                    2 => 0.0132,
                    1 => 0.0136,
                    0 => 0.0139,
                ],
                '-2.1' => [
                    9 => 0.0143,
                    8 => 0.0146,
                    7 => 0.0150,
                    6 => 0.0154,
                    5 => 0.0158,
                    4 => 0.0162,
                    3 => 0.0166,
                    2 => 0.0170,
                    1 => 0.0174,
                    0 => 0.0179,
                ],
                '-2.0' => [
                    9 => 0.0183,
                    8 => 0.0188,
                    7 => 0.0192,
                    6 => 0.0197,
                    5 => 0.0202,
                    4 => 0.0207,
                    3 => 0.0212,
                    2 => 0.0217,
                    1 => 0.0222,
                    0 => 0.0228,
                ],
                '-1.9' => [
                    9 => 0.0233,
                    8 => 0.0239,
                    7 => 0.0244,
                    6 => 0.0250,
                    5 => 0.0256,
                    4 => 0.0262,
                    3 => 0.0268,
                    2 => 0.0274,
                    1 => 0.0281,
                    0 => 0.0287,
                ],
                '-1.8' => [
                    9 => 0.0294,
                    8 => 0.0301,
                    7 => 0.0307,
                    6 => 0.0314,
                    5 => 0.0322,
                    4 => 0.0329,
                    3 => 0.0336,
                    2 => 0.0344,
                    1 => 0.0351,
                    0 => 0.0359,
                ],
                '-1.7' => [
                    9 => 0.0367,
                    8 => 0.0375,
                    7 => 0.0384,
                    6 => 0.0392,
                    5 => 0.0401,
                    4 => 0.0409,
                    3 => 0.0418,
                    2 => 0.0427,
                    1 => 0.0436,
                    0 => 0.0446,
                ],
                '-1.6' => [
                    9 => 0.0455,
                    8 => 0.0465,
                    7 => 0.0475,
                    6 => 0.0485,
                    5 => 0.0495,
                    4 => 0.0505,
                    3 => 0.0516,
                    2 => 0.0526,
                    1 => 0.0537,
                    0 => 0.0548,
                ],
                '-1.5' => [
                    9 => 0.0559,
                    8 => 0.0571,
                    7 => 0.0582,
                    6 => 0.0594,
                    5 => 0.0606,
                    4 => 0.0618,
                    3 => 0.0630,
                    2 => 0.0643,
                    1 => 0.0655,
                    0 => 0.0668,
                ],
                '-1.4' => [
                    9 => 0.0681,
                    8 => 0.0694,
                    7 => 0.0708,
                    6 => 0.0721,
                    5 => 0.0735,
                    4 => 0.0749,
                    3 => 0.0764,
                    2 => 0.0778,
                    1 => 0.0793,
                    0 => 0.0808,
                ],
                '-1.3' => [
                    9 => 0.0823,
                    8 => 0.0838,
                    7 => 0.0853,
                    6 => 0.0869,
                    5 => 0.0885,
                    4 => 0.0901,
                    3 => 0.0918,
                    2 => 0.0934,
                    1 => 0.0951,
                    0 => 0.0968,
                ],
                '-1.2' => [
                    9 => 0.0985,
                    8 => 0.1003,
                    7 => 0.1020,
                    6 => 0.1038,
                    5 => 0.1056,
                    4 => 0.1075,
                    3 => 0.1093,
                    2 => 0.1112,
                    1 => 0.1131,
                    0 => 0.1151,
                ],
                '-1.1' => [
                    9 => 0.1170,
                    8 => 0.1190,
                    7 => 0.1210,
                    6 => 0.1230,
                    5 => 0.1251,
                    4 => 0.1271,
                    3 => 0.1292,
                    2 => 0.1314,
                    1 => 0.1335,
                    0 => 0.1357,
                ],
                '-1.0' => [
                    9 => 0.1379,
                    8 => 0.1401,
                    7 => 0.1423,
                    6 => 0.1446,
                    5 => 0.1469,
                    4 => 0.1492,
                    3 => 0.1515,
                    2 => 0.1539,
                    1 => 0.1562,
                    0 => 0.1587,
                ],
                '-0.9' => [
                    9 => 0.1611,
                    8 => 0.1635,
                    7 => 0.1660,
                    6 => 0.1685,
                    5 => 0.1711,
                    4 => 0.1736,
                    3 => 0.1762,
                    2 => 0.1788,
                    1 => 0.1814,
                    0 => 0.1841,
                ],
                '-0.8' => [
                    9 => 0.1867,
                    8 => 0.1894,
                    7 => 0.1922,
                    6 => 0.1949,
                    5 => 0.1977,
                    4 => 0.2005,
                    3 => 0.2033,
                    2 => 0.2061,
                    1 => 0.2090,
                    0 => 0.2119,
                ],
                '-0.7' => [
                    9 => 0.2148,
                    8 => 0.2177,
                    7 => 0.2206,
                    6 => 0.2236,
                    5 => 0.2266,
                    4 => 0.2296,
                    3 => 0.2327,
                    2 => 0.2358,
                    1 => 0.2389,
                    0 => 0.2420,
                ],
                '-0.6' => [
                    9 => 0.2451,
                    8 => 0.2483,
                    7 => 0.2514,
                    6 => 0.2546,
                    5 => 0.2578,
                    4 => 0.2611,
                    3 => 0.2643,
                    2 => 0.2676,
                    1 => 0.2709,
                    0 => 0.2743,
                ],
                '-0.5' => [
                    9 => 0.2776,
                    8 => 0.2810,
                    7 => 0.2843,
                    6 => 0.2877,
                    5 => 0.2912,
                    4 => 0.2946,
                    3 => 0.2981,
                    2 => 0.3015,
                    1 => 0.3050,
                    0 => 0.3085,
                ],
                '-0.4' => [
                    9 => 0.3121,
                    8 => 0.3156,
                    7 => 0.3192,
                    6 => 0.3228,
                    5 => 0.3264,
                    4 => 0.3300,
                    3 => 0.3336,
                    2 => 0.3372,
                    1 => 0.3409,
                    0 => 0.3446,
                ],
                '-0.3' => [
                    9 => 0.3483,
                    8 => 0.3520,
                    7 => 0.3557,
                    6 => 0.3594,
                    5 => 0.3632,
                    4 => 0.3669,
                    3 => 0.3707,
                    2 => 0.3745,
                    1 => 0.3783,
                    0 => 0.3821,
                ],
                '-0.2' => [
                    9 => 0.3829,
                    8 => 0.3897,
                    7 => 0.3936,
                    6 => 0.3974,
                    5 => 0.4013,
                    4 => 0.4052,
                    3 => 0.4090,
                    2 => 0.4129,
                    1 => 0.4168,
                    0 => 0.4207,
                ],
                '-0.1' => [
                    9 => 0.4247,
                    8 => 0.4286,
                    7 => 0.4325,
                    6 => 0.4364,
                    5 => 0.4404,
                    4 => 0.4443,
                    3 => 0.4483,
                    2 => 0.4522,
                    1 => 0.4562,
                    0 => 0.4602,
                ],
                '-0.0' => [
                    9 => 0.4641,
                    8 => 0.4681,
                    7 => 0.4721,
                    6 => 0.4761,
                    5 => 0.4801,
                    4 => 0.4840,
                    3 => 0.4880,
                    2 => 0.4920,
                    1 => 0.4960,
                    0 => 0.5000,
                ],
                '0.0'  => [
                    0 => 0.50000,
                    1 => 0.50399,
                    2 => 0.50798,
                    3 => 0.51197,
                    4 => 0.51595,
                    5 => 0.51994,
                    6 => 0.52392,
                    7 => 0.52790,
                    8 => 0.53188,
                    9 => 0.53586,
                ],
                '0.1'  => [
                    0 => 0.53980,
                    1 => 0.54380,
                    2 => 0.54776,
                    3 => 0.55172,
                    4 => 0.55567,
                    5 => 0.55966,
                    6 => 0.56360,
                    7 => 0.56749,
                    8 => 0.57142,
                    9 => 0.57535,
                ],
                '0.2'  => [
                    0 => 0.57930,
                    1 => 0.58317,
                    2 => 0.58706,
                    3 => 0.59095,
                    4 => 0.59483,
                    5 => 0.59871,
                    6 => 0.60257,
                    7 => 0.60642,
                    8 => 0.61026,
                    9 => 0.61409,
                ],
                '0.3'  => [
                    0 => 0.61791,
                    1 => 0.62172,
                    2 => 0.62552,
                    3 => 0.62930,
                    4 => 0.63307,
                    5 => 0.63683,
                    6 => 0.64058,
                    7 => 0.64431,
                    8 => 0.64803,
                    9 => 0.65173,
                ],
                '0.4'  => [
                    0 => 0.65542,
                    1 => 0.65910,
                    2 => 0.66276,
                    3 => 0.66640,
                    4 => 0.67003,
                    5 => 0.67364,
                    6 => 0.67724,
                    7 => 0.68082,
                    8 => 0.68439,
                    9 => 0.68793,
                ],
                '0.5'  => [
                    0 => 0.69146,
                    1 => 0.69497,
                    2 => 0.69847,
                    3 => 0.70194,
                    4 => 0.70540,
                    5 => 0.70884,
                    6 => 0.71226,
                    7 => 0.71566,
                    8 => 0.71904,
                    9 => 0.72240,
                ],
                '0.6'  => [
                    0 => 0.72575,
                    1 => 0.72907,
                    2 => 0.73237,
                    3 => 0.73565,
                    4 => 0.73891,
                    5 => 0.74215,
                    6 => 0.74537,
                    7 => 0.74857,
                    8 => 0.75175,
                    9 => 0.75490,
                ],
                '0.7'  => [
                    0 => 0.75804,
                    1 => 0.76115,
                    2 => 0.76424,
                    3 => 0.76730,
                    4 => 0.77035,
                    5 => 0.77337,
                    6 => 0.77637,
                    7 => 0.77935,
                    8 => 0.78230,
                    9 => 0.78524,
                ],
                '0.8'  => [
                    0 => 0.78814,
                    1 => 0.79103,
                    2 => 0.79389,
                    3 => 0.79673,
                    4 => 0.79955,
                    5 => 0.80234,
                    6 => 0.80511,
                    7 => 0.80785,
                    8 => 0.81057,
                    9 => 0.81327,
                ],
                '0.9'  => [
                    0 => 0.81594,
                    1 => 0.81859,
                    2 => 0.82121,
                    3 => 0.82381,
                    4 => 0.82639,
                    5 => 0.82894,
                    6 => 0.83147,
                    7 => 0.83398,
                    8 => 0.83646,
                    9 => 0.83891,
                ],
                '1.0'  => [
                    0 => 0.84134,
                    1 => 0.84375,
                    2 => 0.84614,
                    3 => 0.84849,
                    4 => 0.85083,
                    5 => 0.85314,
                    6 => 0.85543,
                    7 => 0.85769,
                    8 => 0.85993,
                    9 => 0.86214,
                ],
                '1.1'  => [
                    0 => 0.86433,
                    1 => 0.86650,
                    2 => 0.86864,
                    3 => 0.87076,
                    4 => 0.87286,
                    5 => 0.87493,
                    6 => 0.87698,
                    7 => 0.87900,
                    8 => 0.88100,
                    9 => 0.88298,
                ],
                '1.2'  => [
                    0 => 0.88493,
                    1 => 0.88686,
                    2 => 0.88877,
                    3 => 0.89065,
                    4 => 0.89251,
                    5 => 0.89435,
                    6 => 0.89617,
                    7 => 0.89796,
                    8 => 0.89973,
                    9 => 0.90147,
                ],
                '1.3'  => [
                    0 => 0.90320,
                    1 => 0.90490,
                    2 => 0.90658,
                    3 => 0.90824,
                    4 => 0.90988,
                    5 => 0.91149,
                    6 => 0.91308,
                    7 => 0.91466,
                    8 => 0.91621,
                    9 => 0.91774,
                ],
                '1.4'  => [
                    0 => 0.91924,
                    1 => 0.92073,
                    2 => 0.92220,
                    3 => 0.92364,
                    4 => 0.92507,
                    5 => 0.92647,
                    6 => 0.92785,
                    7 => 0.92922,
                    8 => 0.93056,
                    9 => 0.93189,
                ],
                '1.5'  => [
                    0 => 0.93319,
                    1 => 0.93448,
                    2 => 0.93574,
                    3 => 0.93699,
                    4 => 0.93822,
                    5 => 0.93943,
                    6 => 0.94062,
                    7 => 0.94179,
                    8 => 0.94295,
                    9 => 0.94408,
                ],
                '1.6'  => [
                    0 => 0.94520,
                    1 => 0.94630,
                    2 => 0.94738,
                    3 => 0.94845,
                    4 => 0.94950,
                    5 => 0.95053,
                    6 => 0.95154,
                    7 => 0.95254,
                    8 => 0.95352,
                    9 => 0.95449,
                ],
                '1.7'  => [
                    0 => 0.95543,
                    1 => 0.95637,
                    2 => 0.95728,
                    3 => 0.95818,
                    4 => 0.95907,
                    5 => 0.95994,
                    6 => 0.96080,
                    7 => 0.96164,
                    8 => 0.96246,
                    9 => 0.96327,
                ],
                '1.8'  => [
                    0 => 0.96407,
                    1 => 0.96485,
                    2 => 0.96562,
                    3 => 0.96638,
                    4 => 0.96712,
                    5 => 0.96784,
                    6 => 0.96856,
                    7 => 0.96926,
                    8 => 0.96995,
                    9 => 0.97062,
                ],
                '1.9'  => [
                    0 => 0.97128,
                    1 => 0.97193,
                    2 => 0.97257,
                    3 => 0.97320,
                    4 => 0.97381,
                    5 => 0.97441,
                    6 => 0.97500,
                    7 => 0.97558,
                    8 => 0.97615,
                    9 => 0.97670,
                ],
                '2.0'  => [
                    0 => 0.97725,
                    1 => 0.97778,
                    2 => 0.97831,
                    3 => 0.97882,
                    4 => 0.97932,
                    5 => 0.97982,
                    6 => 0.98030,
                    7 => 0.98077,
                    8 => 0.98124,
                    9 => 0.98169,
                ],
                '2.1'  => [
                    0 => 0.98214,
                    1 => 0.98257,
                    2 => 0.98300,
                    3 => 0.98341,
                    4 => 0.98382,
                    5 => 0.98422,
                    6 => 0.98461,
                    7 => 0.98500,
                    8 => 0.98537,
                    9 => 0.98574,
                ],
                '2.2'  => [
                    0 => 0.98610,
                    1 => 0.98645,
                    2 => 0.98679,
                    3 => 0.98713,
                    4 => 0.98745,
                    5 => 0.98778,
                    6 => 0.98809,
                    7 => 0.98840,
                    8 => 0.98870,
                    9 => 0.98899,
                ],
                '2.3'  => [
                    0 => 0.98928,
                    1 => 0.98956,
                    2 => 0.98983,
                    3 => 0.99010,
                    4 => 0.99036,
                    5 => 0.99061,
                    6 => 0.99086,
                    7 => 0.99111,
                    8 => 0.99134,
                    9 => 0.99158,
                ],
                '2.4'  => [
                    0 => 0.99180,
                    1 => 0.99202,
                    2 => 0.99224,
                    3 => 0.99245,
                    4 => 0.99266,
                    5 => 0.99286,
                    6 => 0.99305,
                    7 => 0.99324,
                    8 => 0.99343,
                    9 => 0.99361,
                ],
                '2.5'  => [
                    0 => 0.99379,
                    1 => 0.99396,
                    2 => 0.99413,
                    3 => 0.99430,
                    4 => 0.99446,
                    5 => 0.99461,
                    6 => 0.99477,
                    7 => 0.99492,
                    8 => 0.99506,
                    9 => 0.99520,
                ],
                '2.6'  => [
                    0 => 0.99534,
                    1 => 0.99547,
                    2 => 0.99560,
                    3 => 0.99573,
                    4 => 0.99585,
                    5 => 0.99598,
                    6 => 0.99609,
                    7 => 0.99621,
                    8 => 0.99632,
                    9 => 0.99643,
                ],
                '2.7'  => [
                    0 => 0.99653,
                    1 => 0.99664,
                    2 => 0.99674,
                    3 => 0.99683,
                    4 => 0.99693,
                    5 => 0.99702,
                    6 => 0.99711,
                    7 => 0.99720,
                    8 => 0.99728,
                    9 => 0.99736,
                ],
                '2.8'  => [
                    0 => 0.99744,
                    1 => 0.99752,
                    2 => 0.99760,
                    3 => 0.99767,
                    4 => 0.99774,
                    5 => 0.99781,
                    6 => 0.99788,
                    7 => 0.99795,
                    8 => 0.99801,
                    9 => 0.99807,
                ],
                '2.9'  => [
                    0 => 0.99813,
                    1 => 0.99819,
                    2 => 0.99825,
                    3 => 0.99831,
                    4 => 0.99836,
                    5 => 0.99841,
                    6 => 0.99846,
                    7 => 0.99851,
                    8 => 0.99856,
                    9 => 0.99861,
                ],
                '3.0'  => [
                    0 => 0.99865,
                    1 => 0.99869,
                    2 => 0.99874,
                    3 => 0.99878,
                    4 => 0.99882,
                    5 => 0.99886,
                    6 => 0.99889,
                    7 => 0.99893,
                    8 => 0.99896,
                    9 => 0.99900,
                ],
            ];
        /**
         * Z scores for confidence intervals
         * Key: confidence level %
         * Value: Z score
         *
         * @var array<numeric|string, float>
         */
        private const Z_SCORES_FOR_CONFIDENCE_INTERVALS
            = [
                50     => 0.67449,
                70     => 1.04,
                75     => 1.15035,
                80     => 1.282,
                85     => 1.44,
                90     => 1.64485,
                92     => 1.75,
                95     => 1.95996,
                96     => 2.05,
                97     => 2.17009,
                98     => 2.326,
                99     => 2.57583,
                '99.5' => 2.81,
                '99.8' => 3.08,
                '99.9' => 3.29053,
            ];

        /**
         * Get Z score probability (Φ)
         *
         * @param float $Z
         *
         * @return float probability
         *
         * @throws \MathPHP\Exception\BadParameterException
         */
        public static function getZScoreProbability(float $Z): float
        {
            if ( ! preg_match('/^ (\-? \d [.] \d) (\d) $/x',
                sprintf('%1.2f', $Z), $matches)
            )
            {
                throw new Exception\BadParameterException("Z does not match format X.XX: $Z");
            }
            /**
             * @var string    $z
             * @var int|float $＋0．0x
             */
            [$z, $＋0．0x] = [$matches[1], $matches[2]];

            return self::Z_SCORES[$z][$＋0．0x];
        }

        /**
         * Get Z score for confidence interval
         *
         * @param string $cl confidence level
         *
         * @return float Z score
         *
         * @throws Exception\BadDataException
         */
        public static function getZScoreForConfidenceInterval(string $cl): float
        {
            if ( ! array_key_exists($cl,
                self::Z_SCORES_FOR_CONFIDENCE_INTERVALS)
            )
            {
                throw new Exception\BadDataException('Not a valid confidence level');
            }

            return self::Z_SCORES_FOR_CONFIDENCE_INTERVALS[$cl];
        }
    }
