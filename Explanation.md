# WP Media Crawler - Explanation

## The problem to be solved

From a business-related point of view, the problem is to have a way for analyzing the website (for this test only the home page) backlinks health. Based on the links overview, the site administrator could find ways for improving their WebSite SEO and increase page views.

From an engineering point of view, the problem is related to making sure the administrator will have a good experience while analyzing the page's links. It is also important to make sure the admin analyses the current links, so it has to be up to date. Furthermore, it could use the power of programming languages to filter and delivers the links that are most important for the analysis.

## A technical spec of how I will solve it

Thinking about WordPress and all the tools and package this framework have, the best approach would be to develop a plugin. Regarding the technical requirements, for the first step, I will develop a PoC (proof of concept) to make sure I choose the best crawler engine. This is the only part I'm not sure about what is the best approach. 

After choosing that, I will probably build the final structure for the engine and then develop the whole plugin around it. I usually use OOP to build and test each part of the system separately, and then connect everything afterward (connecting everything and seeing it working is pretty much one of the most satisfactory feelings about Software Engineering).

## The technical decisions you made and why

As I planned, I started with a PoC of the crawler engine. After that, I built everything around it. Using DDD (Domain Driven Development), I tried to split every "context" in a package and then just execute it from a procedural initializer (such as cron job tasks or the admin handler). 

Prematurely, I created the repository when I was doing the PoC, so I named it after it. I kept it so I could save some time, as it was one of the objectives of the technical assignment. As a software engineer, I know the importance of good names. Now, thinking also about the WP Media products, I would name it WP Media SEO, as a sparkle for something great.

### The crawler engine

After searching for the best solutions for crawling links using PHP/WordPress, I chose to use the [Symfony Crawler](https://github.com/symfony/dom-crawler). This lib is by far the most popular and very straightforward. I also could use the native PHP DomDocument class to crawl, but I have some experience with this guy and if I can avoid handling its limitations and weirdness, I go for it.

### Where to save the sitemap.html

First I thought about saving the sitemap.html file in the WP root through the ABSPATH constant or saving it in the wp-content/uploads folders. But I felt like there were better approaches than these. So I decided to look up some solutions made by some big guys. Using Yoast as a reference, I decided to build the sitemap.html in runtime and to serve it through PHP using rewrite rules (as Yoast does with the sitemap.xml). This solution is more secure, easier to test, and much more straightforward.

### Schemas

In Java Spring Boot, it's common to use POJO's (Plain Old Java Objects) for building *Entities*. Those objects represent the stored data in a much higher abstract layer. It is simple and beautiful. I brought to WordPress by the domain of Schemas (it was also based on the WooCommerce schemas). But for WordPress, it is not only suitable for the Model Layer (MVC - Model View Controller design pattern), as it works perfectly for building HTML components. In PHP, almost everything is associative arrays or generic objects, having a well-thought object for serving data can improve a lot the quality of the code in general.

### Package template changes

I needed to adjust some settings of your package template. I mainly changed the composer.json, because some of the libs weren't being found. I did some general changes as well, so I would be able to feel a little bit more comfortable. The changes were the first thing I did and you can follow the main ones here in this commit: https://github.com/Hercilio1/wp-media-crawler/commit/998289dd

I also adjusted some PHP capabilities configurations to be easier to work with PHP 7.4 within PHPCS.

### Unit and Integration tests

I really dig into the WP Rocket testing approach. I'm used to unit test and sometimes I do integration tests for the critical paths, but I was fascinated by the way you test the filesystem and the integration testing using Brain\Monkey. So, if you analyze it, you will see lots of code based on the WP Rocket unit and integration tests.

### Travis CI

The plugin runs in PHP 7.2, 7.3, and 7.4 without any errors, but the Unit tests don't. The problem isn't in my code, though. It is in the Yoast TestCase class (the wp media test library uses it). You can check out the error here: https://app.travis-ci.com/github/Hercilio1/wp-media-crawler/jobs/605690965.
So in order to pass the Travis builds, I disabled the PHP 7.2 verification.

### General overview (about technical decisions)

The rest of the structure is based on my general past experiences and I believe it can get more and more sharpen.

## How the code itself works and why

I might start with the story of Alan Turing (hahaha). Well, let's start covering the sequential execution of the plugin.

1. The plugin loads and immediately hooks the following actions:
	- The "crawl links" task. It executes the task through Cronjobs. During the "crawl links" task hooking, it schedules a cronjob that will run the task at the plugin's activation. It also unschedules it at the plugin's deactivation.
	- The "Sitemap Manager" page registration. 
	- The "crawl links" admin request handler. It executes the task through the administrator's request.
	- The "sitemap.html" router. It serves the sitemap.html.
2. The "crawl links" executions and the sitemap router will use the rest of the structure by demand.

The rest of the structure is covered below.

### The Crawler domain

It is responsible for downloading the webpage and searching for internal links. It was built thinking about scaling with other crawlers, so I created an interface.

The WebpageReader was moved from the Crawler interface so we could use the HTML content of the request page for other actions besides crawling.

The LinksCrawler will return all internal links inside the requested webpage.

### The Filesystem domain

It allows to store statical files inside the `wp-content/uploads/wp-media/` folder. It can be used to store, retrieve, and check if exists
and delete files from this folder.

It is used by the "crawl links" executions to store the downloaded page. It covers the following technical requirement: 'Save the home page’s .php file as a .html file.'.

### The Sitemap domain

It covers three features. 

1. SitemapBuilder. Builds the HTML of the sitemap.html. 
2. SitemapRouter. It servers the built sitemap.html when requested by HTTP (e.g. http://example.org/sitemap.html).
3. SitemapLinksStorage. It is the statical class responsible for doing the CRUD into the database of the crawled links. It also registers and retrieves the timestamp when the links are stored.

### The Exceptions domain

It is used for delivering the best feedback on the possible errors for the admin (who executed the "crawl links" task) or even the tech support (through logs).

### The Schemas domain

Used for managing the links, it abstracts the stored data and offers some helpful methods for displaying data (good for the database and HTML generator).

### The "Crawl Links" execution/task

Here is where the puzzle of classes is solved.

1. First we delete the previous data from the database and the stored webpage html.
2. Request the webpage (for this test, only the home page) using Crawlers\WebpageReader.
3. Retrieve the links inside the webpage using Crawlers\LinksCrawler, which returns a list of Schemas\Link.
4. Store the links in the database using Sitemap\SitemapLinksRouter with a Schemas\LinksRecord object.
5. Store the request page as a .html file using Filesystem\File.

If an error happens, an exception will be thrown. Those exceptions are then caught by the class that triggered the execution/task.

### The Sitemap.html Router

Handled by Sitemap\SitemapRouter, it outputs the HTML of the sitemap using the Sitemap\SitemapBuilder for building the HTML from the stored links (retrieved by the Sitemap\SitemapLinksRouter). If any error happens, it will return the 404 page.

## How your solution achieves the admin’s desired outcome per the user story

I could improve the algorithm that displays the links on the admin page so it would deliver a sitemap much smarter for the admin. The story doesn't cover it, but thinking about SEO it is important to analyze what is not good, as much as we check what is good.

I also think that with some sprints or iterations with the stakeholders, we could dig into the problems and build a much more sophisticated solution.

However, thinking of this task as an MVP (or as a simple approach to the problem solution), it solves the problem with mastery. 
