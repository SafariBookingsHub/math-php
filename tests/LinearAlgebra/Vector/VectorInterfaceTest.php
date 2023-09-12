<?php

    namespace MathPHP\Tests\LinearAlgebra\Vector;

    use MathPHP\Exception;
    use MathPHP\LinearAlgebra\Vector;
    use PHPUnit\Framework\TestCase;

    use function is_int;

    class VectorInterfaceTest extends TestCase {
        /** @var array */
        private array $A;

        /** @var Vector */
        private Vector $V;

        public static function dataProviderForCountable(): array
        {
            return [
                [[1], 1],
                [[1, 1], 2],
                [[1, 1, 1], 3],
                [[1, 1, 1, 1], 4],
                [[1, 1, 1, 1, 1], 5],
                [[1, 1, 1, 1, 1, 1], 6],
                [[1, 1, 1, 1, 1, 1, 1], 7],
                [[1, 1, 1, 1, 1, 1, 1, 1], 8],
                [[1, 1, 1, 1, 1, 1, 1, 1, 1], 9],
                [[1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 10],
            ];
        }

        public static function dataProviderForJsonSerializable(): array
        {
            return [
                [
                    [1],
                    '[1]',
                ],
                [
                    [1, 2, 3],
                    '[1,2,3]',
                ],
            ];
        }

        public function setUp(): void
        {
            // Given
            $this->A = [1, 2, 3, 4, 5];
            try
            {
                $this->V = new Vector($this->A);
            } catch (Exception\BadDataException $e)
            {
            }
        }

        /**
         * @test Interfaces
         */
        public function testInterfaces()
        {
            // Given
            $interfaces = class_implements(object_or_class: '\MathPHP\LinearAlgebra\Vector');

            // Then
            $this->assertContains('Countable', $interfaces);
            $this->assertContains('ArrayAccess', $interfaces);
            $this->assertContains('JsonSerializable', $interfaces);
        }

        /**
         * @test ArrayAccess interface
         */
        public function testArrayAccessInterfaceOffsetGet()
        {
            // Then
            $this->assertEquals(1, $this->V[0]);
            $this->assertEquals(2, $this->V[1]);
            $this->assertEquals(3, $this->V[2]);
            $this->assertEquals(4, $this->V[3]);
            $this->assertEquals(5, $this->V[4]);
        }

        /**
         * @test ArrayAccess interface
         */
        public function testArrayAccessInterfaceOffsetSet()
        {
            // Then
            $this->expectException(Exception\VectorException::class);

            // When
            $this->V[0] = 1;
        }

        /**
         * @test ArrayAccess interface
         */
        public function testArrayAccessOffsetExists()
        {
            // Then
            $this->assertTrue($this->V->offsetExists(i: 0));
        }

        /**
         * @test ArrayAccess interface
         */
        public function testArrayAccessOffsetUnsetException()
        {
            // Then
            $this->expectException(Exception\VectorException::class);

            // When
            unset($this->V[0]);
        }

        /**
         * @test         Countable interface
         * @dataProvider dataProviderForCountable
         *
         * @param array $A
         * @param int   $n
         */
        public function testCountableInterface(array $A, int $n)
        {
            // Given
            try
            {
                $V = new Vector($A);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            $count = count(value: $V);

            // Then
            $this->assertEquals($n, $count);
        }

        /**
         * @test         JsonSerializable interface
         * @dataProvider dataProviderForJsonSerializable
         *
         * @param array  $A
         * @param string $json
         */
        public function testJsonSerializable(array $A, string $json)
        {
            // Given
            try
            {
                $A = new Vector($A);
            } catch (Exception\BadDataException $e)
            {
            }

            // When
            $jsonString = json_encode(value: $A);

            // Then
            $this->assertEquals($json, $jsonString);
        }

        /**
         * @test Iteration
         */
        public function testIteration()
        {
            // When
            foreach ($this->V as $element)
                $this->assertTrue(is_int($element));

            // When Rewinding
            foreach ($this->V as $element)
                $this->assertTrue(is_int($element));
        }

        public function testIteratorKeys()
        {
            // When
            foreach ($this->V as $k => $v)
                $this->assertSame($v, $this->A[$k]);
        }
    }
