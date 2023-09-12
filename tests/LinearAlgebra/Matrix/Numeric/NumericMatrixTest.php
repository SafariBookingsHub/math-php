<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Numeric;

    use MathPHP\Exception\BadDataException;
    use MathPHP\LinearAlgebra\NumericMatrix;
    use PHPUnit\Framework\TestCase;

    use const PHP_EOL;

    class NumericMatrixTest extends TestCase {
        /**
         * @test Object type of numeric matrix
         */
        public function testGetObjectType()
        {
            // Given
            try
            {
                $A = new NumericMatrix(A: [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (BadDataException $e)
            {
            }

            // When
            $objectType = $A->getObjectType();

            // Then
            $this->assertSame('number', $objectType);
        }

        /**
         * @test string representation
         */
        public function testGetStringRepresentation()
        {
            // Given
            try
            {
                $A = new NumericMatrix(A: [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ]);
            } catch (BadDataException $e)
            {
            }

            // And
            $expected = "[1, 2, 3]\n[2, 3, 4]\n[3, 4, 5]";

            // When
            $stringRepresentation = (string)$A;

            // Then
            $this->assertEquals($expected, $stringRepresentation);
        }

        /**
         * @test debug Info
         */
        public function testDebugInfo()
        {
            // Given
            try
            {
                $A = new NumericMatrix(A: [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [3, 4, 5, 6],
                ]);
            } catch (BadDataException $e)
            {
            }

            // When
            $debugInfo = $A->__debugInfo();

            // Then
            $this->assertEquals('3x4', $debugInfo['matrix']);
            $this->assertEquals(PHP_EOL.$A, $debugInfo['data']);
            $this->assertEquals($A->getError(), $debugInfo['ε']);
        }
    }
