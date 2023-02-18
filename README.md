# Description

This plugin offers a convenient way to reference [RequestTracker](https://bestpractical.com/) tickets and articles.

Instead of using absolute links to tickets, you can use the common short-hand `RT<TicketNumber>` for tickets
and `RTA<ArticleNumber>` for articles.

## Usage

To link to a particular ticket, just use the string `RT<TicketNumber>`, replacing `<TicketNumber>` with
the RequestTracker ticket number. This will then be replaced with a link, e.g.

`RT12345` will be replaced with [RT Ticket #12345](https://helpdesk.example.com/Ticket/Display.html?id=12345)

Same principal applies to RT Articles, using `RTA<ArticleNumber>`.

`RTA123` will be replaces with [RT Article #123](https://helpdesk.example.com/Articles/Article/Display.html?id=12345)


## Configuration

You can configure the URL for your RequestTracker instance in the `Configuration Manager` of DokuWiki.

Enjoy!
