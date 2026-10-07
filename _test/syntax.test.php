<?php

/**
 * Tests for the rtlink plugin
 *
 * @group plugin_rtlink
 * @group plugins
 */
class syntax_plugin_rtlink_test extends DokuWikiTest
{
    protected $pluginsEnabled = ['rtlink'];

    public function testTicketLink()
    {
        $html = p_render('xhtml', p_get_instructions('See RT123 now'), $info);
        $this->assertStringContainsString('href="https://helpdesk.example.com/Ticket/Display.html?id=123"', $html);
        $this->assertStringContainsString('RT Ticket #123', $html);
    }

    public function testArticleLink()
    {
        $html = p_render('xhtml', p_get_instructions('See rta45 now'), $info);
        $this->assertStringContainsString('href="https://helpdesk.example.com/Articles/Article/Display.html?id=45"', $html);
        $this->assertStringContainsString('RT Article #45', $html);
    }

    public function testNoMatchInsideWords()
    {
        foreach (['ART12', 'RT12abc', 'RT'] as $text) {
            $html = p_render('xhtml', p_get_instructions($text), $info);
            $this->assertStringNotContainsString('helpdesk.example.com', $html, $text);
        }
    }

    public function testPlainTextFallback()
    {
        $text = p_render('text', p_get_instructions('See RT123 now'), $info);
        $this->assertStringContainsString('RT123', $text);
    }
}
