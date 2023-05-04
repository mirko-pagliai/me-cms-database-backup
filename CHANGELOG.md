# 1.x branch
## 1.2 branch
### 1.2.0
* the old `MenuHelper` class has been removed and replaced with `BackupsMenuHelper`. The helper is set in the bootstrap,
  with the `MeCms/DatabaseBackup.MenuHelpers`, as requested by `me-cms` 2.32.0;
* as for `me-cms` 2.32.0 and `me-tools` 2.24.0, it uses `Configure::readFromPlugins()` with `WritableDirs` key instead of
  the old `WRITABLE_DIRS` key.

## 1.1 branch
### 1.1.1
* updated for me-cms 2.31.9.

### 1.1.0
* updated for me-cms 2.31.0.

## 1.0 branch
### 1.0.5
* updated for me-cms 2.30.10.

### 1.0.4-RC4
* small and numerous improvements of descriptions, tags and code suggested by
  PhpStorm.

### 1.0.3-RC3
* numerous code adjustments for improvement and adaptation to PHP 7.4 new features;
* updated for PHP 8.1 Requires at least PHP 7.4.

### 1.0.2-RC2
* little fixes.

### 1.0.1-RC1
* fixed I18n translations.

### 1.0.0-beta1
* first release.
