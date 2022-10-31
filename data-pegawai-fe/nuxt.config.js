require('dotenv').config()
const { join } = require('path')
const { copySync, removeSync } = require('fs-extra')
const webpack = require('webpack')

module.exports = {
    ssr: false,

    srcDir: __dirname,

    env: {
        apiUrl: process.env.API_URL || process.env.APP_URL + '/api',
        appName: process.env.APP_NAME || 'Laravel Nuxt',
        appLocale: process.env.APP_LOCALE || 'en',
        baseRoute: process.env.BASE_ROUTE || "/",
        githubAuth: !!process.env.GITHUB_CLIENT_ID
    },

    head: {
        title: process.env.APP_NAME,
        titleTemplate: '%s - ' + process.env.APP_NAME,
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: 'Badan Kepegawaian Daerah Pemerintah Provinsi Jawa Barat' }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
        ]
    },

    loading: { color: '#007bff' },

    router: {
        base: process.env.BASE_ROUTE || "/",
        middleware: ['locale', 'check-auth']
    },

    css: [
        { src: '~assets/sass/skote/app.scss', lang: 'scss' }
    ],

    plugins: [
        '~components/global',
        '~plugins/i18n',
        '~plugins/vform',
        '~plugins/axios',
        '~plugins/fontawesome',
        '~plugins/nuxt-client-init',
        '~plugins/bootstrap-vue',
        '~plugins/filters',
        '~plugins/vselect',
        '~plugins/datepicker',
        '~plugins/check-permission',
        '~plugins/apexcharts',
        { src: '~plugins/bootstrap', mode: 'client' },
        { src: '~/plugins/underscore', ssr: false },
        { src: '~/plugins/vue-calendar.js', mode: 'client' },
    ],

    modules: [
        '@nuxtjs/router'
    ],

    build: {
        postcss: null,
        extractCSS: true,
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery'
            })
        ],
        extend(config, ctx) {}
    },

    hooks: {
        generate: {
            done(generator) {
                // Copy dist files to public/_nuxt
                if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
                    const publicDir = join(generator.nuxt.options.rootDir, 'public', '_nuxt')
                    removeSync(publicDir)
                    copySync(join(generator.nuxt.options.generate.dir, '_nuxt'), publicDir)
                    copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDir, 'index.html'))
                    removeSync(generator.nuxt.options.generate.dir)
                }
            }
        }
    }
}