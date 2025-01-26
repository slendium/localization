# Localization
An implementation-agnostic standard for localization in PHP. It is intended to facilitate the exchange of localizable information between PHP frameworks and libraries. The primary motivation for creating this standard is to provide common interfaces for other Slendium libraries, so it will be biased towards these use-cases. Suggestions are always welcome.

Requires PHP 8.4+ and assumes the same standards that PHP uses natively ([RFC 4646, CLDR](https://www.php.net/manual/en/class.locale.php)). It also assumes use of a static analyzer such as [PHPStan](https://phpstan.org/).

## The problem
There are different ways of doing localization in PHP (array dictionaries, [gettext](https://www.php.net/manual/en/book.gettext.php), framework-specific implementations, etc.). Sometimes end-users can enter their own translations on a per-object basis - for example, using a database table. Code that is responsible for producing a locale-specific output (such as an HTML page, a JSON object, a PDF, an e-mail, etc.), or other libraries that want to work with localizable objects, should not become dependent on the underlying dictionary implementation of such objects. Common interfaces prevent this dependency.

## Example of a basic use-case
Consider the following application-specific object which has a `$name` property that should change based on the current locale.

```PHP
class Product {
  /** @var Localizable<non-empty-string> */
  public Localizable $name;
}
```
Now consider the following class for generating an outgoing e-mail. Imagine `EmailGenerator` is a template class provided by a framework which returns an intermediate representation of the structure of the e-mail to be sent. The framework will later convert this structure to the actual text to be sent, using a localization algorithm of its own choosing. The implementor of this API does not have to worry about the specifics of localizing this e-mail, but can focus instead on its general structure.
```PHP
class OrderEmailGenerator extends EmailGenerator {

  public function __construct(
    /** @var list<Product> */
    private array $products
  ) { }

  #[Override]
  public function generateBody(): iterable {
    yield $this->getSalutation($this->getCustomerName()); // combines localizable information with a customer name
    yield '<br><br>';
    yield $this->translate('thanks_for_ordering'); // a Localizable from a static dictionary
    yield from [ '<table><tr><th>', $this->translate('product_category'), '</th></tr>' ];
    foreach ($this->products as $product) {
      yield from [ '<tr><td>', $product->name, '</td></tr>' ];
    }
    yield '</table>';
    yield $this->getSignature(); // another localizable
  }
}
```

## Post-processing
Work in progress.

## Fallback values
Work in progress.

## Pluralization
Work in progress.
