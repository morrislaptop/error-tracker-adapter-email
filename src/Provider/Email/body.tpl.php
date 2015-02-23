<?php
/**
 * @var Exception $e
 * @var array $extra
 */
?>
# <?php echo $e->getMessage(); ?>

<?php echo $e->getFile(); ?> at line <?php echo $e->getLine(); ?>

```php
<?php echo implode("\n", $this->getCode($e->getFile(), $e->getLine(), 12)); ?>
```

# Stack Trace

```
<?php echo (string) $e; ?>

```

# Context

```
<?php echo $this->dump($extra); ?>

```

# Globals

```
<?php echo $this->dump([$GLOBALS]); ?>

```