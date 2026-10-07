<?php

/**
 * Plugin rtlink: Links to Request Tracker tickets
 *
 * Thanks to Stefan Hechenberger for the inspiration through his websvn plugin
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gregg Berkholtz <gregg@tocici.com>, Tobias <info@hopeconsultants.org>
 */

// must be run within DokuWiki
if (!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_rtlink extends DokuWiki_Syntax_Plugin
{
    protected const ARTICLE = 'article';
    protected const TICKET = 'ticket';

    /**
     * What kind of syntax are we?
     */
    public function getType()
    {
        return 'substition';
    }

    /**
     * Where to sort in?
     */
    public function getSort()
    {
        return 921;
    }

    /**
     * Connect pattern to lexer
     *
     * @param string $mode
     */
    public function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('\b[rR][tT][0-9]+\b', $mode, substr(get_class($this), 7));
        $this->Lexer->addSpecialPattern('\b[rR][tT][aA][0-9]+\b', $mode, substr(get_class($this), 7));
    }

    /**
     * Handle the match
     *
     * @param string $match The text matched by the patterns
     * @param int $state The lexer state for the match
     * @param int $pos The character position of the matched text
     * @param Doku_Handler $handler The Doku_Handler object
     * @return  array Return an array with all data you want to use in render
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        preg_match('/\b([rR][tT][aA]?)([0-9]+)\b/', $match, $matches);
        if (strcasecmp($matches[1], 'RTA') == 0) {
            return array(self::ARTICLE, $matches[2], $match);
        } else {
            return array(self::TICKET, $matches[2], $match);
        }
    }

    /**
     * Create output
     *
     * @param string $mode output format being rendered
     * @param Doku_Renderer $renderer the current renderer object
     * @param array $data data created by handler()
     * @return boolean rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        list($type, $id, $match) = $data;

        // other renderers (text, odt, ...) get the plain text instead of nothing
        if ($mode !== 'xhtml') {
            $renderer->cdata($match);
            return true;
        }

        $rt_url = rtrim($this->getConf('rtlink_rt_url'), '/') . '/';
        switch ($type) {
            case self::ARTICLE:
                $url = $rt_url . 'Articles/Article/Display.html?id=' . $id;
                $text = $this->getConf('rtlink_article_text');
                break;
            default:
                $url = $rt_url . 'Ticket/Display.html?id=' . $id;
                $text = $this->getConf('rtlink_ticket_text');
        }

        // externallink() escapes URL and title and applies the configured link settings
        $renderer->externallink($url, str_replace('%s', $id, $text));
        return true;
    }
}
