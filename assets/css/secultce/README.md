# @sweetalert2/theme-secultce - Secultce Theme for [SweetAlert2](https://github.com/sweetalert2/sweetalert2)

[![npm version](https://img.shields.io/npm/v/@sweetalert2/theme-secultce.svg)](https://www.npmjs.com/package/@sweetalert2/theme-secultce)

![](https://sweetalert2.github.io/images/themes-secultce.png)

Installation
------------

<!-- ```sh
npm install --save sweetalert2 @sweetalert2/theme-secultce
``` -->

```sh
git clone git@github.com:ronnyjohnti/sweetalert2-themes
```
### Build
```sh
cd sweetalert2/secultce
npm run build
```

Usage
-----

With CSS:

Copy the file `secultce.min.css` in `dist` to your project and include:

```html
<!-- Include the Secultce theme -->
<link rel="stylesheet" href="<your-path-project-styles>/secultce.min.css">

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js
"></script>
```
<!--
With SASS:

`your-app.js`:
```js
import Swal from 'sweetalert2/dist/sweetalert2.js';
```

`your-app.scss`:
```scss
@import '@sweetalert2/theme-secultce/secultce.scss';
```
