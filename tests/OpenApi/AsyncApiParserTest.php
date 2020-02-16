<?php

namespace Pccomponentes\OpenApiMessagingContext\Tests\OpenApi;

use Pccomponentes\OpenApiMessagingContext\OpenApi\AsyncApiParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

final class AsyncApiParserTest extends TestCase
{
    /** @test */
    public function given_valid_schema_when_parse_then_get_parsed_schema()
    {
        $allSpec = Yaml::parse(file_get_contents(__DIR__ . '/valid-asyncapi-spec.yaml'));
        $schema = (new AsyncApiParser($allSpec))->parse('pccomponentes.test.testtopic');
        $jsonCompleted = '{"type":"object","required":["message_id","type"],"properties":{"message_id":{"type":"string"},"type":{"type":"string"},"attributes":{"type":"object","required":["some_attribute"],"properties":{"some_attribute":{"type":"string"}}}}}';
        $this->assertJsonStringEqualsJsonString(\json_encode($schema), $jsonCompleted);
    }

    /** @test */
    public function given_valid_schema_when_parse_non_existent_topic_then_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Topic with name <non.existent.topic> not found');
        $allSpec = Yaml::parse(file_get_contents(__DIR__ . '/valid-asyncapi-spec.yaml'));
        (new AsyncApiParser($allSpec))->parse('non.existent.topic');
    }
}
