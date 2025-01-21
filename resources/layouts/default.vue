<template>
    <app-layout>
        <dialog-confirm
            v-model="confirmLogout"
            :message="t('messages.timer_running')"
            confirm-label="Log Out"
            :on-confirm="() => router.post(route('logout'))"
        />
        <template #header>
            <v-navigation-drawer
                ref="nav"
                v-model="drawer"
                v-model:rail="rail"
                :permanent="!mobile"
                :expand-on-hover="!mobile"
            >
                <v-list>
                    <v-list-item prepend-icon="mdi-account">
                        <v-list-item-title
                            :aria-label="`${t('Name')}: ${user?.name}`"
                        >
                            {{ user?.name }}
                        </v-list-item-title>
                        <v-list-item-subtitle
                            :aria-label="`${t('Email')}: ${user?.email}`"
                        >
                            {{ user?.email }}
                        </v-list-item-subtitle>
                        <template #append>
                            <v-list-item-action>
                                <v-btn
                                    icon
                                    size="small"
                                    variant="text"
                                    :aria-label="t('Log Out')"
                                    @click="onLogout"
                                >
                                    <v-icon icon="mdi-logout" />
                                </v-btn>
                            </v-list-item-action>
                        </template>
                    </v-list-item>
                </v-list>
                <v-divider />
                <navigation-menu @close="closeNav" />
                <template #append>
                    <v-list>
                        <locale-switcher variant="list" />
                    </v-list>
                </template>
            </v-navigation-drawer>
            <v-app-bar color="primary">
                <template v-if="mobile" #prepend>
                    <v-app-bar-nav-icon
                        tabindex="-1"
                        @click.stop="drawer = !drawer"
                    />
                </template>
                <v-toolbar-title>
                    <span :aria-label="camelPad(name)" translate="no">
                        {{ name }}
                    </span>
                </v-toolbar-title>
                <v-spacer />
                <router-link
                    :href="route('time-log.create')"
                    :aria-label="t('Create Time Log')"
                    :aria-current="
                        router.matches('time-log.create') ? 'page' : false
                    "
                >
                    <v-btn icon tabindex="-1" aria-hidden="true">
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
import AppLayout from '@/layouts/app.vue'
import { useTimerStore } from '@/stores/timer'
import { useDisplay } from 'vuetify'

defineOptions({ name: 'DefaultLayout' })

// Product name
const name = import.meta.env.VITE_APP_NAME

// Composables
const { t } = useI18n()
const { mobile } = useDisplay()
const user = useProperty('auth.user')

// Refs
const hasFocus = ref(false)
const nav = useTemplateRef('nav')
const drawer = ref(!mobile.value)
const rail = ref(!mobile.value && !hasFocus.value)
const confirmLogout = ref(false)

// Open navigation on mobile
watch(mobile, (val) => {
    if (!val) drawer.value = true
    rail.value = !val && !hasFocus.value
})

watch(hasFocus, (val) => {
    rail.value = !mobile.value && !val
})

watch(drawer, () => {
    rail.value = !mobile.value && !hasFocus.value
})

const closeNav = () => {
    if (mobile.value) drawer.value = false
}

// Ask logout confirmation when timer is active

const timerStore = useTimerStore()

const onLogout = () => {
    if (timerStore.startTime) confirmLogout.value = true
    else router.post(route('logout'))
}

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
