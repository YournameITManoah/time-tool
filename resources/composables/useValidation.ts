/**
 * Use validation rules with i18n
 * @returns Validation rules
 */
export function useValidation() {
    const { t } = useI18n()

    const isRequired = (v: string | number | boolean) =>
        (v !== undefined && v !== null && v !== '') || t('validation.required')

    return { isRequired }
}
