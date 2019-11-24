<?php
declare(strict_types=1);


namespace fall\core\lang;

use fall\core\utils\PHPUtils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
final class ClassBuilder
{
  private $className;
  private $namespace;

  private $extendedClassName = null;
  private $implementedInterfaceNames = [];

  private $constructor = null;
  private $destructor = null;
  private $properties = [];
  private $methods = [];

  public function __construct($className, $namespace)
  {
    $this->className = $className;
    $this->setNamespace($namespace);
  }

  public function setNamespace($namespace): ClassBuilder
  {
    $this->namespace = $namespace;
    return $this;
  }

  public function setConstructor($visibility = 'public', array $parameters = [], $content = ''): ClassBuilder
  {
    $this->constructor = array(
      'visibility' => $visibility,
      'parameters' => $parameters,
      'content' => $content
    );
    return $this;
  }

  public function addConstructorContent($content)
  {
    $this->constructor['content'] .= $content;
  }

  public function addConstructorParameter($parameter): ClassBuilder
  {
    if ($this->constructor === null) {
      $this->setConstructor();
    }

    $this->constructor['parameters'][] = '$' . $parameter;
    $this->addConstructorContent('$this->' . "{$parameter} = $" . $parameter . ';');
    return $this;
  }

  public function setDestructor($content = ''): ClassBuilder
  {
    $this->destructor = [
      'content' => $content
    ];
    return $this;
  }

  public function addProperty($propertyName, $visibility = 'private', $withGetter = true, $withSetter = true, $value = null): ClassBuilder
  {
    $this->properties[$propertyName] = [
      'visibility' => $visibility,
      'withGetter' => $withGetter,
      'withSetter' => $withSetter,
      'value' => $value
    ];

    return $this;
  }

  /**
   * @param $methodName
   * @param $visibility
   * @param $parameters
   * @param $content
   */
  public function addMethod($methodName, $visibility = 'public', array $parameters = [], $content = ''): ClassBuilder
  {
    $this->methods[$methodName] = [
      'visibility' => $visibility,
      'parameters' => $parameters,
      'content' => $content
    ];
    return $this;
  }

  public function extendClass($extendedClassName): ClassBuilder
  {
    $this->extendedClassName = $extendedClassName;
    return $this;
  }

  public function implementInterface($implementedInterfaceName): ClassBuilder
  {
    $this->implementedInterfaceNames[] = $implementedInterfaceName;
    return $this;
  }

  public function __toString()
  {
    $classFieldDefinition = '';
    $classGetterSetterDefinition = '';
    foreach ($this->properties as $propertyName => $propertyDefinition) {
      $classFieldDefinition .= $propertyDefinition['visibility'] . ' $' . $propertyName . (isset($propertyDefinition['value']) ? ' = ' . $propertyDefinition['value'] : '') . ';' . PHP_EOL;
      if ($propertyDefinition['withGetter']) {
        $classGetterSetterDefinition .= 'public function get' . ucfirst($propertyName) . '() {return $this->' . $propertyName . ';}' . PHP_EOL;
      }
      if ($propertyDefinition['withGetter']) {
        $classGetterSetterDefinition .= 'public function set' . ucfirst($propertyName) . '($' . $propertyName . ') {$this->' . $propertyName . ' = $' . $propertyName . ';}' . PHP_EOL;
      }
    }

    $classConstructorDefinition = '';
    if ($this->constructor != null) {
      $classConstructorDefinition .= $this->constructor['visibility'] . ' function __construct(' . \join(',', $this->constructor['parameters']) . ') {' . $this->constructor['content'] . '}' . PHP_EOL;
    }

    $classDestructorDefinition = '';
    if ($this->destructor != null) {
      $classDestructorDefinition .= 'public function __destruct() {' . $this->destructor['content'] . '}' . PHP_EOL;
    }

    $classMethodDefinition = '';
    foreach ($this->methods as $methodName => $methodDefinition) {
      $classMethodDefinition .= $methodDefinition['visibility'] . ' function ' . $methodName . '(' . \join(',', $methodDefinition['parameters']) . ') {' . $methodDefinition['content'] . '}' . PHP_EOL;
    }

    $namespaceDefinition = '';
    if ($this->namespace != null) {
      $namespaceDefinition = 'namespace ' . $this->namespace . ';';
    }

    $extendedClassDefinition = '';
    if ($this->extendedClassName != null) {
      $extendedClassDefinition = ' extends ' . $this->extendedClassName;
    }

    $implementedInterfaceDefinition = '';
    if (!empty($this->implementedInterfaceNames)) {
      $implementedInterfaceDefinition = ' implements ' . implode(', ', $this->implementedInterfaceNames);
    }

    return <<<EOT
<?php
declare(strict_types=1);

{$namespaceDefinition}
final class {$this->className}{$extendedClassDefinition}{$implementedInterfaceDefinition} {
$classFieldDefinition $classConstructorDefinition $classDestructorDefinition $classGetterSetterDefinition $classMethodDefinition
}
EOT;
  }

  public function build()
  {
    PHPUtils::addDynamicCode($this->__toString());
  }
}
