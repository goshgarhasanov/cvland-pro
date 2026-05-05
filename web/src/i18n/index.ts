import { createI18n } from 'vue-i18n'

import az from './locales/az.json'
import en from './locales/en.json'
import ru from './locales/ru.json'

export type AppLocale = 'az' | 'en' | 'ru'

const stored = localStorage.getItem('locale') as AppLocale | null
const initial: AppLocale = stored && ['az', 'en', 'ru'].includes(stored) ? stored : 'az'

export const i18n = createI18n({
  legacy: false,
  locale: initial,
  fallbackLocale: 'az',
  messages: { en, az, ru },
})
