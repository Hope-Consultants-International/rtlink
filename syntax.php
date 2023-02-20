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
            return array(self::ARTICLE, $matches[2]);
        } else {
            return array(self::TICKET, $matches[2]);
        }
    }

    /**
     * Create output
     *
     * @param string $format output format being rendered
     * @param Doku_Renderer $renderer the current renderer object
     * @param array $data data created by handler()
     * @return boolean rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        if ($mode !== 'xhtml') {
            return true;
        }

        $rt_url = rtrim($this->getConf('rtlink_rt_url'), '/') . '/';
        list($type, $id) = $data;
        switch ($type) {
            case self::ARTICLE:
                $link = sprintf($this->getConf('rtlink_article_text'), $id);
                $renderer->doc .= sprintf(
                    '<a href="%sArticles/Article/Display.html?id=%s">%s</a>',
                    $rt_url,
                    $id,
                    $link
                );
                break;
            case self::TICKET:
                $link = sprintf($this->getConf('rtlink_ticket_text'), $id);
                $renderer->doc .= sprintf(
                    '<a href="%sTicket/Display.html?id=%s">%s</a>',
                    $rt_url,
                    $id,
                    $link
                );
                break;
        }
        return true;
    }
}
