# Game-On

Game-On is an educational framework that provides teachers with a vast amount of tools to create their own <a href="http://en.wikipedia.org/wiki/Gamification" target="_blank">gamified</a> learning system.

<a href="http://maclab.guhsd.net/game-on" target="_blank">Game-On Documentation</a>

<a href="http://edex.adobe.com/group/game-on/discussions/" target="_blank">Adobe Education Exchange Game-On Group Forum</a>

<a href="http://edex.adobe.com/group/game-on/discussion/2fcaa2/" target="_blank">Adobe Education Exchange Updates Thread</a>

If you think Game-On could be improved or could use a new feature, drop us a <a href="http://edex.adobe.com/group/game-on/discussion/8610bf/" target="_blank">feature request</a> and we'll see what we can do!

If you find a bug or need assistance while using Game-On, please check the <a href='http://edex.adobe.com/group/game-on/discussion/684285c0/' target='_blank'>known conflicts</a> thread to see if your situation has appeared before.  If you believe you've found a new bug, please report it to our <a href='http://edex.adobe.com/group/game-on/discussion/4a137977/' target='_blank'>tech support</a> thread.

* * *

### For Contributors

#### Syntax

We are sticking very closely to the <a href="http://make.wordpress.org/core/handbook/coding-standards/php/" target="_blank">Wordpress syntax standards</a>; however, we have made some changes of our own that we feel are more logical.

Everything except for using `echo` (on that in a minute), should follow the K&R variation "the one true brace style" (<a href="http://en.wikipedia.org/wiki/Indent_style#Variant:_1TBS" target="_blank">1TBS</a>).

**Naming Conventions**

Functions, variables, etc. in all languages should use the format: go_*function_name*.  Lowercase with underscores to separate words.

**Functions**

All functions will be declared using the "go_" prefix followed by a unique name, and then the parameters as shown below.

```
function go_add($param1, $param2) {
	return($param1 + $param2);
}
```

When adding hooks try to keep the <a href="http://codex.wordpress.org/Function_Reference/add_action" target="_blank">`add_action()`</a> or <a href="http://codex.wordpress.org/Function_Reference/add_filter" target="_blank">`add_filter()`</a> calls right on top of their respective functions.  It makes them easier to find.  If you have written an ajax call in jQuery make sure to link to it in game_on.php using the format `add_action('wp_ajax_go_function_that_the_ajax_is_calling', 'go_function_that_the_ajax_is_calling');`.

Although calling a function before it is declared in JavaScript and PHP is "wrong", it is allowed and it is easier to read, so we'll stick with it.

**Variables**

When declaring variables always make sure that you've given the variable a unique name, one that has no chance of being overridden by another function.  This is mostly an issue with JavaScript script tags.  Global JavaScript variables with very generic names such as "number" or "count" have the potential to be overridden and break things.

**If/else if Statements**

When writing if/else statements your spacing should look like the following:

```
if (!empty($param1) && $param1 === true) {
	// do something
} else {
	// do something else
}
```

A space between the `if` keyword, the aruments and the starting curly brace `{`, then a newline and a tab before writing the code for the condition.  The `else` condition should be on the same line as the closing `if` curly brace.

**Echoing data in PHP**

When echoing strings for example "Hello World", doing the following would be fine.

```
echo 'Hello World';
```

Please try to be consistent and use the single quotes `''` when echoing string literals.  Whereas when we want to echo out variables, we want to instead use (double quotes in) this format:

```
$str = 'Hello World!';
// YES
echo $str;
// YES
echo "{$str} What's new?";
// NO
echo $str.'What\'s new?';
```

Embeding variables in our `echo` statements using the double quote and curly brace format is great because we can even access arrays, and objects using this format.  *Unfortunately we cannot use it to embed functions.*  For instance:

```
// will NOT work
echo "{go_add(1, 2)}";
// will work
echo '1 + 2 = '.go_add(1, 2);
```

When echoing large blocks of code, for example an ajax call when clicking a button, space your echo calls like this:

```
echo "
	<script type='text/javascript'>
		jQuery(document).ready(fuction() {
			jQuery('#the_button').click(function() {
				jQuery.ajax({
					type: "POST",
					data:{
						action: 'go_func_does_stuff',
					},
					success: function() {
						alert("hello world!");
					}
				});
			});
		});
	</script>
";
```

One `"` above and one below, just like the curly braces of a function!

**JavaScript/jQuery**

JavaScript/jQuery code should always be set below the HTML elements that it is affecting, if not the code should be called inside a <a href="http://api.jquery.com/ready/" target="_blank">`jQuery(document).ready()`</a> anonymous function.  That ensures that the code will be called after all elements are loaded on the page.

Another tip is to create JavaScript functions when possible so that you aren't unknowingly overriding variables in the global scope or duplicating code that is used in multiple places in the code.  For example in <a href="https://github.com/TheMacLab/game-on/blob/master/types/tasks/task_shortcode.php" target="_blank">`types/tasks/task_shortcode.php`</a> we have a rather large script tag in the `go_task_shortcode()` function.  In that script tag we have multiple functions that use very similar data such as the stage that a task is in; however, we don't want these variables to override one another, so we stick them into separate functions.

* * *
#### Documentation

Please leave a newline for all comments and if the comment is describing a function leave a space above the comment to make it easier to read.  Comments should be short and sweet.