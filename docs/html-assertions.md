# HTML Assertions

Tesing HTML output can be very tedious. The Joomla! test environment offers a convenient alternative. It adds a couple of methods and especially assertions to the default PHPUnit test case using a class `HtmlTestCase`. This testcase class itself is 100% unit tested, so it can be considered to be reliable.

## Methods

### `getRootElement()`

This method extracts the outermost element from an HTML string. It will fail if there is not exactly one element on the outer level, which is intended.

### `normalise()`

This method tries to apply a uniform formatting to an HTML string, so it is suitable for comparision. The only drawback is that it needs to use Tidy's repair option, so you must have the tidy extension installed and it will - as the name says - repair eventually malformed HTML.

### `stripComments()`

This method strips all XML comments from an HTML string.

### `assertHtmlEquals()`

This method takes two HTML string and compares them after normalisation with `normalise()`.

### `assertHtmlHasRoot()`

This method checks if there is exactly one element on the outer level.

### `assertHtmlRootHasClass()`

This method checks, if the root element has a given class. It will find the class among others, if needed.

### `assertHtmlRootHasId()`

This method checks, if the root element has a given id.
