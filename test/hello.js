var page = require('webpage').create();

var url = 'http://tracker.dev:8000/auth/login';
// var url = 'http://tracker.jennyswiftcreations.com/auth/login';
var email = 'cheezyspaghetti@gmail.com';
var password = 'abcdefg';
var exercisesPage = 'http://tracker.dev:8000/#/exercises';

// var casper = require('casper').create();

casper.test.begin('Foods are shown', 3, function suite(test) {
    casper.start(url, function() {
        this.echo("I'm loaded.");
    });

    casper.viewport(1024, 768);

    casper.waitForSelector("form input[name='email']", function() {
        this.fillSelectors('form', {
            'input[name = email ]' : email,
            'input[name = password ]' : password
        }, true);

    });

    casper.waitForSelector('#entries', function () {
        // this.capture('test.png');
        this.echo('should be testing');
        // this.echo(this.getHTML('#entries'));
        this.setContent(this.getHTML('#entries'));
        test.assertEquals(6, 6);
        // test.assertExists('<td>apple</td>');
        test.assertTextExists('apple');
    });

    casper.thenOpen(exercisesPage, function () {
        this.echo('exercises page opened');
    });

    casper.waitForSelector('.table-bordered', function () {
        casper.wait(5000, function () {
            // this.capture('test.png');
            test.assertTextExists('back lever');
        });

    });

    casper.run(function() {
        test.done();
        this.echo('So the whole suite ended.');
        // this.exit();
    });
});





