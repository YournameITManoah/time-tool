<template>
    <app-layout>
        <template #header>
            <v-navigation-drawer
                ref="nav"
                v-model="drawer"
                :permanent="!mobile"
                :rail="!mobile && !hasFocus"
                expand-on-hover
            >
                <v-list>
                    <v-list-item
                        :subtitle="user?.email"
                        :title="user?.name"
                        prepend-icon="mdi-account"
                    >
                        <template #append>
                            <v-list-item-action>
                                <router-link
                                    :href="route('logout')"
                                    as="div"
                                    method="post"
                                >
                                    <v-btn icon size="small" variant="text">
                                        <v-icon icon="mdi-logout" />
                                    </v-btn>
                                </router-link>
                            </v-list-item-action>
                        </template>
                    </v-list-item>
                </v-list>
                <v-divider />
                <navigation-menu />
                <template #append>
                    <v-list>
                        <locale-switcher />
                    </v-list>
                </template>
            </v-navigation-drawer>
            <v-app-bar color="primary">
                <template v-if="mobile" #prepend>
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
                </template>
                <v-toolbar-title :text="name" />
                <v-spacer />
                <router-link :href="route('time-log.create')">
                    <v-btn icon tabindex="-1">
                        <v-icon icon="mdi-plus" />
                    </v-btn>
                </router-link>
            </v-app-bar>
        </template>
        <v-container>
            <slot />
            <div style="height: 48px" />
        </v-container>
        <template #footer>
            <timer-fab />
        </template>
    </app-layout>
</template>
<script setup lang="ts">
import { useDisplay } from 'vuetify'
import AppLayout from '@/layouts/app.vue'

defineOptions({ name: 'DefaultLayout' })

// Product name
const name = import.meta.env.VITE_APP_NAME

// Composables
const { mobile } = useDisplay()
const user = useProperty('auth.user')

// Refs
const hasFocus = ref(false)
const nav = useTemplateRef('nav')
const drawer = ref(!mobile.value)

// Open navigation on mobile
watch(mobile, (val) => {
    if (!val) drawer.value = true
})

// Fix keyboard accessibility

const onFocusIn = () => {
    hasFocus.value = true
    drawer.value = true
}

const onFocusOut = () => {
    hasFocus.value = false
    if (mobile.value) drawer.value = false
}
onMounted(() => {
    nav.value?.$el.nextSibling.addEventListener('focusin', onFocusIn)
    nav.value?.$el.nextSibling.addEventListener('focusout', onFocusOut)
})

onBeforeUnmount(() => {
    nav.value?.$el.nextSibling.removeEventListener('focusin', onFocusIn)
    nav.value?.$el.nextSibling.removeEventListener('focusout', onFocusOut)
})
</script>
