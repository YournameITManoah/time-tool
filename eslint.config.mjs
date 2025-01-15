import pluginJs from '@eslint/js'
import eslintConfigPrettier from 'eslint-config-prettier'
import perfectionist from 'eslint-plugin-perfectionist'
import pluginVue from 'eslint-plugin-vue'
import globals from 'globals'
import tslint from 'typescript-eslint'

export default [
    { ignores: ['node_modules/', 'vendor/', 'public/'] },
    { files: ['**/*.{js,mjs,cjs,ts,mts,vue}'] },
    { languageOptions: { globals: { ...globals.browser, ...globals.node } } },
    {
        files: ['**/*.vue'],
        languageOptions: { parserOptions: { parser: tslint.parser } },
    },
    pluginJs.configs.recommended,
    ...tslint.configs.strict,
    ...pluginVue.configs['flat/recommended'],
    perfectionist.configs['recommended-natural'],
    eslintConfigPrettier,
    { rules: { 'no-undef': 'off' } },
]
