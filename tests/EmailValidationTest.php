<?php
namespace Actinity\Sanitiser\Test;

use Actinity\Sanitiser\Sanitiser;
use PHPUnit\Framework\TestCase;

class ParseColumnsTest extends TestCase
{
    public function clean($input) {

        return Sanitiser::clean($input);

    }

    public function testASimpleAddress()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("test@dell.com")
        );
    }

    public function testARegularAddress()
    {
        $this->assertEquals(
            'Kieran.Ashley_Fella@actinity-test.example.com',
            $this->clean("Kieran.Ashley_Fella@actinity-test.example.com")
        );
    }

    /**
     * @group chars
     */
    public function testCharacters()
    {
        $this->assertEquals(
            "Test.NAME12@dell.com",
            $this->clean("Test.NAME12@dell.com")
        );

    }

    public function testWithWhitespace()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("test@dell.com ")
        );
    }

    public function testTrailingSemiColon()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("test@dell.com;")
        );
    }

    /**
     * @group stop
     */
    public function testTrailingFullStop()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("test@dell.com.")
        );
    }

    public function testTrailingComma()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("test@dell.com,")
        );
    }

    public function testWrappedBraces()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("<test@dell.com>")
        );
    }

    public function testWrappedBrackets()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("(test@dell.com)")
        );
    }

    public function testWrappedSquares()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("[test@dell.com]")
        );
    }

    public function testNameWithBraces()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("Test Man <test@dell.com>")
        );
    }

    public function testMultipleAddresses()
    {
        $this->assertFalse($this->clean("test@dell.com address2@dell.com"));
    }

    public function testWrappedQuotes()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean('"test@dell.com"')
        );
    }

    public function testWrappedPrimes()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("'test@dell.com'")
        );
    }

    public function testSafePrime()
    {
        $this->assertEquals(
            "paolo.o'greco@dell.com",
            $this->clean("paolo.o'greco@dell.com")
        );
    }

    public function testWrappedPrimesWithInner()
    {
        $this->assertEquals(
            "paolo.o'greco@dell.com",
            $this->clean("'paolo.o'greco@dell.com'")
        );
    }


    public function testLeadingPrime()
    {
        $this->assertEquals(
            "'paolo.o'greco@dell.com",
            $this->clean("'paolo.o'greco@dell.com")
        );
    }

    public function testDumbString()
    {
        $this->assertEquals(
            "",
            $this->clean("adfaadfjaj")
        );

    }

    public function testASingleAtSymbol()
    {
        $this->assertEquals(
            "",
            $this->clean("@")
        );
    }

    public function testMissingDomain()
    {
        $this->assertEquals(
            "",
            $this->clean("jon@")
        );
    }

    public function testInvalidDomain()
    {
        $this->assertEquals(
            "",
            $this->clean("jon@dell")
        );
    }

    public function testMissingUser()
    {
        $this->assertEquals(
            "",
            $this->clean("@dell.com")
        );
    }

    public function testDotAtEndOfUser()
    {
        $this->assertEquals(
            "",
            $this->clean("test.@dell.com")
        );
    }

    public function testMailToAddress()
    {
        $this->assertEquals(
            "test@dell.com",
            $this->clean("mailto:test@dell.com")
        );
    }

    public function testSingleDots()
    {
        $this->assertFalse($this->clean(".@."));
    }

    public function testSingleDotUser()
    {
        $this->assertFalse($this->clean(".@dell.com"));
    }

    public function testSingleDotDomain()
    {
        $this->assertFalse($this->clean("test@."));
    }

    public function testDoubleDots()
    {
        $this->assertFalse($this->clean("..@.."));
    }

    public function testDoubleDotUser()
    {
        $this->assertFalse($this->clean("..@dell.com"));
    }

    public function testDoubleDotDomain()
    {
        $this->assertFalse($this->clean("test@.."));
    }

    public function testMultipleDots()
    {
        $this->assertFalse($this->clean("......@......."));
    }

    /**
     * @group pain
     */

    public function testBeingAPain()
    {
        $this->assertFalse(
             $this->clean("harry@place.com <script type></script>")
        );

        $this->assertFalse(
            $this->clean("harry@place.com<script type></script>")
        );
    }

    public function testInvalidTLD()
    {
        $this->assertFalse(
            $this->clean("harry@place.com<script")
        );

        $this->assertFalse(
            $this->clean("harry@com<script")
        );
    }

    /**
     * @group tld
     */
    public function testInternationalisedTLD()
    {
        $this->assertEquals(
            "harry@test.xn--qxa6a",
            $this->clean("harry@test.xn--qxa6a")
        );
    }


}