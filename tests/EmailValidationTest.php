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
            "test@example.com",
            $this->clean("test@example.com")
        );
    }

    public function testARegularAddress()
    {
        $this->assertEquals(
            'Jimmy.Ramone_Sinatra@actinity-test.example.com',
            $this->clean("Jimmy.Ramone_Sinatra@actinity-test.example.com")
        );
    }

    public function testAMultipartDomain()
    {
        $this->assertEquals(
            'test@actinity.co.uk',
            $this->clean('test@actinity.co.uk')
        );
    }

    public function testAMultipartDomainWithACom()
    {
        $this->assertEquals(
            'test@actinity.com.uk',
            $this->clean('test@actinity.com.uk')
        );
    }

    /**
     * @group whitespace
     */
    public function testWithNonBreakingWhitespace()
    {
        $this->assertEquals(
            'test@example.com',
            $this->clean('test@example.com  ')
        );
    }

    /**
     * @group whitespace
     */

    public function testWithZeroWidthWhitespace()
    {
        $this->assertEquals(
            'test@example.com',
            $this->clean('test@example.com ​')
        );
    }

	/**
	 * @group whitespace
	 */

	public function testWithInlineWhitespace()
	{
		$this->assertEquals(
			'test.user.email@example.com',
			$this->clean('test. user .email@­example.​com')
		);
	}

    /**
     * @group whitespace
     */
    public function testWithSoftHyphenWhitespace()
    {
        $this->assertEquals(
            'test@example.com',
            $this->clean('test@example.com­')
        );
    }

    /**
     * @group chars
     */
    public function testCharacters()
    {
        $this->assertEquals(
            "Test.NAME12@example.com",
            $this->clean("Test.NAME12@example.com")
        );

    }

    public function testWithWhitespace()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("test@example.com ")
        );
    }

    public function testTrailingSemiColon()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("test@example.com;")
        );
    }

    /**
     * @group stop
     */
    public function testTrailingFullStop()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("test@example.com.")
        );
    }

    public function testTrailingComma()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("test@example.com,")
        );
    }

    public function testWrappedBraces()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("<test@example.com>")
        );
    }

    public function testWrappedBrackets()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("(test@example.com)")
        );
    }

    public function testWrappedSquares()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("[test@example.com]")
        );
    }

    public function testNameWithBraces()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("Test Man <test@example.com>")
        );
    }

    public function testMultipleAddresses()
    {
        $this->assertFalse($this->clean("test@example.com address2@example.com"));
    }

    public function testWrappedQuotes()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean('"test@example.com"')
        );
    }

    public function testWrappedPrimes()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("'test@example.com'")
        );
    }

    public function testSafePrime()
    {
        $this->assertEquals(
            "pauly.o'greco@example.com",
            $this->clean("pauly.o'greco@example.com")
        );
    }

    public function testWrappedPrimesWithInner()
    {
        $this->assertEquals(
            "pauly.o'greco@example.com",
            $this->clean("'pauly.o'greco@example.com'")
        );
    }


    public function testLeadingPrime()
    {
        $this->assertEquals(
            "'pauly.o'greco@example.com",
            $this->clean("'pauly.o'greco@example.com")
        );
    }

    public function testDumbString()
    {
        $this->assertFalse(
            $this->clean("adfaadfjaj")
        );

    }

    public function testASingleAtSymbol()
    {
        $this->assertFalse(
            $this->clean("@")
        );
    }

    public function testMissingDomain()
    {
        $this->assertFalse(
            $this->clean("jon@")
        );
    }

    public function testInvalidDomain()
    {
        $this->assertFalse(
            $this->clean("jon@example")
        );
    }

    public function testMissingUser()
    {
        $this->assertFalse(
            $this->clean("@example.com")
        );
    }

    public function testDotAtEndOfUser()
    {
        $this->assertFalse(
            $this->clean("test.@example.com")
        );
    }

    public function testMailToAddress()
    {
        $this->assertEquals(
            "test@example.com",
            $this->clean("mailto:test@example.com")
        );
    }

    public function testSingleDots()
    {
        $this->assertFalse($this->clean(".@."));
    }

    public function testSingleDotUser()
    {
        $this->assertFalse($this->clean(".@example.com"));
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
        $this->assertFalse($this->clean("..@example.com"));
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
            $this->clean("harry@example.com<script")
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

    public function testDoubleZeroesArePreserved()
    {
        $this->assertEquals(
            "harry001@example.com",
            $this->clean("harry001@example.com")
        );
    }


}