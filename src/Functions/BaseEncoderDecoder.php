<?php

    namespace MathPHP\Functions;

    use MathPHP\Exception;
    use MathPHP\Number\ArbitraryInteger;

    use function chr;
    use function intdiv;
    use function ord;
    use function strlen;
    use function strpos;

    /**
     * Utility functions to manipulate numerical strings with non-standard bases and alphabets
     */
    class BaseEncoderDecoder {
        /**
         * Convert to an arbitrary base and alphabet
         *
         * @param ArbitraryInteger $number
         * @param int              $base
         * @param string|null      $alphabet
         *
         * @return string
         *
         * @throws Exception\BadParameterException if the base is greater than 256
         */
        public static function toBase(
            ArbitraryInteger $number,
            int $base,
            string $alphabet = NULL
        ): string {
            if ($base > 256)
                throw new Exception\BadParameterException("Number base cannot be greater than 256.");
            if ($alphabet === NULL)
                $alphabet = self::getDefaultAlphabet($base);

            $base_256 = $number->toBinary();
            $result = '';

            while ($base_256 !== '')
            {
                $carry = 0;
                $next_int = $base_256;
                $len = strlen($base_256);
                $base_256 = '';

                for ($i = 0; $i < $len; $i++)
                {
                    $chr = ord($next_int[$i]);
                    $int = intdiv($chr + 256 * $carry, $base);
                    $carry = ($chr + 256 * $carry) % $base;
                    // or just trim off all leading chr(0)s
                    if ($base_256 !== '' || $int > 0)
                        $base_256 .= chr($int);
                }
                if (strlen($alphabet) == 1)
                    $result = chr(ord($alphabet) + $carry).$result; else
                    $result = $alphabet[$carry].$result;
            }

            return $result;
        }

        /**
         * Get the default alphabet for a given number base
         *
         * @param int $base
         *
         * @return string offset
         */
        protected static function getDefaultAlphabet(int $base): string
        {
            return match ($base)
            {
                2, 8, 10 => '0',
                16 => '0123456789abcdef',
                default => chr(0),
            };
        }

        /**
         * Create an ArbitraryInteger from a number string in novel number bases and alphabets
         *
         * @param string      $number
         * @param int         $base
         * @param string|null $offset
         *
         * @return ArbitraryInteger
         *
         * @throws \MathPHP\Exception\BadParameterException if the string is empty or base is greater than 256
         */
        public static function createArbitraryInteger(
            string $number,
            int $base,
            string $offset = NULL
        ): ArbitraryInteger {
            if ($number == '')
                throw new Exception\BadParameterException("String cannot be empty.");
            if ($base > 256)
                throw new Exception\BadParameterException("Number base cannot be greater than 256");
            // Set to default offset and ascii alphabet
            if ($offset === NULL)
                $offset = self::getDefaultAlphabet($base);

            $length = strlen($number);

            // Remove the offset.
            if ($offset !== chr(0))
                if (strlen($offset) == 1)
                {
                    // Subtract a constant offset from each character.
                    $offset_num = ord($offset);
                    for ($i = 0; $i < $length; $i++)
                    {
                        $chr = $number[$i];
                        $digit = ord($chr) - $offset_num;
                        // Check that all elements are greater than the offset, and are members of the alphabet.
                        if (($digit < 0) || ($digit >= $base))
                            throw new Exception\BadParameterException("Invalid character in string.");
                        $number[$i] = chr(ord($chr) - $offset_num);
                    }
                } else
                {
                    // Lookup the offset from the string position
                    for ($i = 0; $i < $length; $i++)
                    {
                        $chr = $number[$i];
                        $pos = strpos($offset, $chr);
                        if ($pos === FALSE)
                            throw new Exception\BadParameterException("Invalid character in string.");
                        $number[$i] = chr($pos);
                    }
                }
            // Convert to base 256
            try
            {
                $base256 = new ArbitraryInteger(0);
            } catch (Exception\BadParameterException $e)
            {
            } catch (Exception\IncorrectTypeException $e)
            {
            }
            $length = strlen($number);
            for ($i = 0; $i < $length; $i++)
            {
                $chr = ord($number[$i]);
                try
                {
                    $base256 = $base256->multiply($base)->add($chr);
                } catch (Exception\BadParameterException $e)
                {
                } catch (Exception\IncorrectTypeException $e)
                {
                }
            }

            return $base256;
        }

        public function invalidCharInStringException()
        {
        }

        public function invalidBaseException()
        {
        }

        public function emptyStringException()
        {
        }

        public function invalidToBaseException()
        {
        }

        public function createArbitrary()
        {
        }
    }
