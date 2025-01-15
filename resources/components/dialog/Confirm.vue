<template>
    <v-dialog v-model="open" :max-width="600" persistent>
        <v-card :title="t(title)" :text="message">
            <v-card-actions>
                <v-btn color="error" :text="t(cancelLabel)" @click="cancel" />
                <v-btn
                    color="success"
                    :text="t(confirmLabel)"
                    @click="confirm"
                />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">
defineOptions({ name: 'DialogConfirm' })

const { t } = useI18n()

const open = defineModel<boolean>()

const props = withDefaults(
    defineProps<{
        cancelLabel?: string
        confirmLabel?: string
        message: string
        onCancel?: () => void
        onConfirm?: () => void
        title?: string
    }>(),
    {
        cancelLabel: 'Cancel',
        confirmLabel: 'Confirm',
        onCancel: () => {},
        onConfirm: () => {},
        title: 'Are you sure?',
    },
)

const confirm = () => {
    props.onConfirm()
    open.value = false
}

const cancel = () => {
    props.onCancel()
    open.value = false
}
</script>
