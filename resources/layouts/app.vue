<template>
    <v-app :class="{ 'bg-grey-lighten-4': !theme.current.value.dark }">
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
import { useTheme } from 'vuetify'
import { toast, VSonner } from 'vuetify-sonner'
import 'vuetify-sonner/style.css'

defineOptions({ name: 'AppLayout' })

// Composables
const i18n = useI18n()
const theme = useTheme()
const flash = useProperty('flash')
const locale = useProperty('locale')
const prefersDark = usePreferredDark()

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
watchImmediate(prefersDark, (val) => {
    theme.global.name.value = val ? 'dark' : 'light'
})
</script>
