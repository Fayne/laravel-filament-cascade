<p align="center"><img src="https://res.cloudinary.com/fayne/image/upload/v1787643269/fayne_logo.png" width="300"></p>

# Laravel Filament Cascade

简体中文 | [English](docs/README.en.md)

级联选择字段，适用于 **PHP 8.4+、Laravel 13、Filament 5**。

## 功能与边界

支持静态树形选项及配置许可的文件、目录、URL 数据源，服务端校验完整选择路径。独立于 CMS Core 和 Page Builder。

## 安装

先在宿主 Composer 配置本私有包及所需 `fayne/*` 依赖的软件源，再执行：

```bash
composer require fayne/laravel-filament-cascade
```

Laravel 自动发现服务提供者。仅提供字段的包无需注册 Panel 插件；业务包按下面的示例注册。依赖版本以 [composer.json](composer.json) 为准，不要复制开发宿主的私有仓库凭据。

## 使用与配置

```php
use Fayne\Cascade\Forms\Components\CascadeField;

CascadeField::make('location')->cascadeOptions([
    'options' => [
        ['value' => 'north', 'label' => '北区', 'children' => [
            ['value' => 'coast', 'label' => '沿海', 'leaf' => true],
        ]],
    ],
]);
```

动态数据源需先发布配置 `php artisan vendor:publish --tag=filament-cascade-config`，在 `config/filament-cascade.php` 的 `sources.files`、`sources.directories`、`sources.urls` 中明确许可，再使用 `fromFile`、`fromDirectory` 或 `fromUrl`。不要把任意客户端路径或网址作为可信数据源。

## 测试与上线

本包已提供独立 Testbench 启动测试（`composer test`）；在宿主验证字段渲染、输入、保存后回显、无效值拒绝和权限边界。不要将文档检查视为运行时验收。

## 合作与贡献

通过 Issue 描述需求或缺陷，提供复现步骤、版本和预期行为；提交合并请求时补充相关验证，并说明尚未验证的环境。请勿提交密钥或业务敏感数据。商业合作及授权请联系 [Fayne Guo](mailto:fayne.guo@gmail.com)。

## 许可证

采用[专有许可证](LICENSE.md)。未经授权不得复制、分发或使用；第三方依赖仍遵循各自许可证。

## 开发与安全

[开发与 CI 约定](CONTRIBUTING.md) · [安全报告](SECURITY.md)。运行 `composer check` 检查语法和格式；有独立套件时同时运行测试。包不提交 `composer.lock`。
