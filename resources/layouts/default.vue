<template>
    <app-layout>
        <dialog-confirm
            v-model="confirmLogout"
            :message="t('messages.timer_running')"
            confirm-label="Log Out"
            :on-confirm="() => router.post('logout')"
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
                    <v-list-item
                        :subtitle="user?.email"
                        :title="user?.name"
                        prepend-icon="mdi-account"
                    >
                        <template #append>
                            <v-list-item-action>
                                <v-btn
                                    icon
                                    size="small"
                                    variant="text"
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
    else router.post('logout')
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
