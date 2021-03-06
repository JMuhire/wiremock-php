<?php

namespace WireMock\Client;

use WireMock\Matching\UrlMatchingStrategy;

class RequestPatternBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testMethodAndUrlMatchingStrategyAreInArray()
    {
        // given
        $method = 'GET';
        $matchingType = 'url';
        $matchingValue = '/some/url';
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array($matchingType => $matchingValue));
        $requestPatternBuilder = new RequestPatternBuilder($method, $mockUrlMatchingStrategy);

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('method', $method));
        assertThat($requestPatternArray, hasEntry($matchingType, $matchingValue));
    }

    public function testHeaderWithValueMatchingStrategyIsInArrayIfSpecified()
    {
        // given
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array('url' => '/some/url'));
        $requestPatternBuilder = new RequestPatternBuilder('GET', $mockUrlMatchingStrategy);
        /** @var ValueMatchingStrategy $mockValueMatchingStrategy */
        $mockValueMatchingStrategy = mock('WireMock\Client\ValueMatchingStrategy');
        when($mockValueMatchingStrategy->toArray())->return(array('equalTo' => 'something'));
        $requestPatternBuilder->withHeader('Some-Header', $mockValueMatchingStrategy);

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('headers', array('Some-Header' => array('equalTo' => 'something'))));
    }

    public function testHeaderAbsenceIsInArrayIfSpecified()
    {
        // given
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array('url' => '/some/url'));
        $requestPatternBuilder = new RequestPatternBuilder('GET', $mockUrlMatchingStrategy);
        $requestPatternBuilder->withoutHeader('Some-Header');

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('headers', array('Some-Header' => array('absent' => true))));
    }

    public function testCookieWithValueMatchingStrategyIsInArrayIfSpecified()
    {
        // given
        $requestPatternBuilder = new RequestPatternBuilder('GET', new UrlMatchingStrategy('url', '/some/url'));
        $requestPatternBuilder->withCookie('aCookie', new ValueMatchingStrategy('equalTo', 'something'));

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('cookies', array('aCookie' => array('equalTo' => 'something'))));
    }

    public function testRequestBodyPatternsAreInArrayIfSpecified()
    {
        // given
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array('url' => '/some/url'));
        $requestPatternBuilder = new RequestPatternBuilder('GET', $mockUrlMatchingStrategy);
        $requestPatternBuilder->withRequestBody(new ValueMatchingStrategy('equalTo', 'aValue'));

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('bodyPatterns', array(array('equalTo' => 'aValue'))));
    }

    public function testBasicAuthIsInArrayIfSpecified()
    {
        // given
        /** @var UrlMatchingStrategy $mockUrlMatchingStrategy */
        $mockUrlMatchingStrategy = mock('WireMock\Matching\UrlMatchingStrategy');
        when($mockUrlMatchingStrategy->toArray())->return(array('url' => '/some/url'));
        $requestPatternBuilder = new RequestPatternBuilder('GET', $mockUrlMatchingStrategy);
        $requestPatternBuilder->withBasicAuth('uname', 'pword');

        // when
        $requestPatternArray = $requestPatternBuilder->build()->toArray();

        // then
        assertThat($requestPatternArray, hasEntry('basicAuthCredentials',
            array('username' => 'uname', 'password' => 'pword')));
    }

    public function testBuilderCanBeCreatedWithCustomMatcherNameAndParams()
    {
        // when
        $builder = new RequestPatternBuilder('custom-matcher', array('param' => 'val'));

        // then
        $pattern = $builder->build();
        assertThat($pattern->getMethod(), nullValue());
        assertThat($pattern->getUrlMatchingStrategy(), nullValue());
        assertThat($pattern->getCustomMatcherDefinition()->toArray(), equalTo(array(
            'name' => 'custom-matcher',
            'parameters' => array('param' => 'val')
        )));
    }

    public function testCustomMatcherDefinitionIsInArrayIfSpecified()
    {
        // given
        $builder = new RequestPatternBuilder('GET', new UrlMatchingStrategy('url', '/some/url'));

        // when
        $builder->withCustomMatcher('custom-matcher', array('param' => 'val'));

        // then
        $pattern = $builder->build();
        assertThat($pattern->getCustomMatcherDefinition()->toArray(), equalTo(array(
            'name' => 'custom-matcher',
            'parameters' => array('param' => 'val')
        )));
    }
}
