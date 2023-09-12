<?php

    namespace MathPHP\Tests\LinearAlgebra\Matrix\Complex;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\ComplexMatrix;
    use MathPHP\Number\ArbitraryInteger;
    use MathPHP\Number\Complex;
    use MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;
    use PHPUnit\Framework\TestCase;

    class ComplexMatrixTest extends TestCase {
        use MatrixDataProvider;

        /**
         * @test         Construction
         * @dataProvider dataProviderForComplexObjectMatrix
         *
         * @param Complex[][] $A
         */
        public function testConstruction(array $A)
        {
            // When
            try
            {
                $A = new ComplexMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->assertInstanceOf(ComplexMatrix::class, $A);
        }

        /**
         * @test Constructor exception when non complex number provided
         */
        public function testConstructorException()
        {
            // Given
            try
            {
                $A = [
                    [new Complex(r: 2, i: 1), new Complex(r: 2, i: 1)],
                    [new Complex(r: 2, i: 1), new ArbitraryInteger(number: 4)],
                ];
            } catch (Exception\BadParameterException|Exception\IncorrectTypeException $e)
            {
            }

            // Then
            $this->expectException(Exception\IncorrectTypeException::class);

            // When
            try
            {
                $A = new ComplexMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }
        }

        /**
         * @test createZeroValue
         */
        public function testCreateZeroValue()
        {
            // Given
            $zeroMatrix = ComplexMatrix::createZeroValue();

            // And
            $expected = [
                [new Complex(r: 0, i: 0)],
            ];

            // Then
            $this->assertEquals($expected, $zeroMatrix->getMatrix());
        }

        /**
         * @test conjugateTranspose
         */
        public function testConjugateTranspose()
        {
            // Given
            $A = [
                [
                    new Complex(r: 1, i: 0),
                    new Complex(r: -2, i: -1),
                    new Complex(r: 5,
                        i: 0),
                ],
                [
                    new Complex(r: 1, i: 1),
                    new Complex(r: 0, i: 1),
                    new Complex(r: 4,
                        i: -2),
                ],
            ];
            try
            {
                $A = new ComplexMatrix(A: $A);
            } catch (Exception\BadDataException|Exception\MathException|Exception\IncorrectTypeException $e)
            {
            }

            // And
            $expected = [
                [new Complex(r: 1, i: 0), new Complex(r: 1, i: -1)],
                [new Complex(r: -2, i: 1), new Complex(r: 0, i: -1)],
                [new Complex(r: 5, i: 0), new Complex(r: 4, i: 2)],
            ];

            // When
            $Aᴴ = $A->conjugateTranspose();

            // Then
            $this->assertEquals($expected, $Aᴴ->getMatrix());
        }
    }
