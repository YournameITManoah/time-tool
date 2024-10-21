import globals from 'globals'
import pluginJs from '@eslint/js'
import tseslint from 'typescript-eslint'
import pluginVue from 'eslint-plugin-vue'
import eslintConfigPrettier from 'eslint-config-prettier'

export default [
    { ignores: ['node_modules/', 'vendor/', 'public/'] },
    { files: ['**/*.{js,mjs,cjs,ts,mts,vue}'] },
    { languageOptions: { globals: { ...globals.browser, ...globals.node } } },
    {
        files: ['**/*.vue'],
        languageOptions: { parserOptions: { parser: tseslint.parser } },
    },
    pluginJs.configs.recommended,
    ...tseslint.configs.strict,
    ...pluginVue.configs['flat/recommended'],
    eslintConfigPrettier,
    { rules: { 'no-undef': 'off' } },
]
