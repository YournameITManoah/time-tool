<template>
    <v-app :class="{ 'bg-grey-lighten-4': !vuetifyTheme.current.value.dark }">
        <v-sonner
            position="top-right"
            expand
            :container-aria-label="i18n.t('Notifications')"
        />
        <slot name="header" />
        <v-main scrollable>
            <slot />
        </v-main>
        <slot name="footer" />
    </v-app>
</template>
<script setup lang="ts">
import { useGeneralStore } from '@/stores/general'
import { storeToRefs } from 'pinia'
import { useTheme } from 'vuetify'
import { toast, VSonner } from 'vuetify-sonner'
import 'vuetify-sonner/style.css'

defineOptions({ name: 'AppLayout' })

// Composables
const i18n = useI18n()
const vuetifyTheme = useTheme()
const flash = useProperty('flash')
const locale = useProperty('locale')
const prefersDark = usePreferredDark()
const { theme } = storeToRefs(useGeneralStore())

// When notifications change, display them
watchImmediate(flash, (val) => {
    if (val) {
        Object.keys(val).forEach((key) => {
            const msg = val[key as keyof typeof val]
            if (msg) toast(msg, { cardProps: { color: key } })
        })
    }
})

// Update internal locale
watchImmediate(locale, (val) => {
    i18n.locale.value = val
})

// Update internal theme
const setTheme = (dark: boolean) => {
    vuetifyTheme.global.name.value = dark ? 'dark' : 'light'
}

watchImmediate(prefersDark, (val) => {
    if (theme.value === 'system') setTheme(val)
})

watchImmediate(theme, (val) => {
    setTheme(val === 'system' ? prefersDark.value : val === 'dark')
})
</script>
