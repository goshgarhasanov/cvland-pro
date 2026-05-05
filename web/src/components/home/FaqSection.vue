<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t, tm, rt } = useI18n()

interface FaqItem { q: string; a: string }

const items = computed<FaqItem[]>(() => {
  const raw = tm('home.faq.items') as FaqItem[]
  return raw.map((item) => ({ q: rt(item.q), a: rt(item.a) }))
})

const openIdx = ref<number | null>(0)

function toggle(idx: number) {
  openIdx.value = openIdx.value === idx ? null : idx
}
</script>

<template>
  <section id="faq" class="scroll-mt-24 py-24">
    <div class="mx-auto max-w-3xl px-6">
      <div class="text-center">
        <span class="kicker">{{ t('home.faq.kicker') }}</span>
        <h2 class="mt-4 section-title">{{ t('home.faq.title') }}</h2>
      </div>

      <div class="mt-12 space-y-3">
        <div
          v-for="(item, idx) in items"
          :key="idx"
          class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 transition-colors"
          :class="openIdx === idx ? 'border-brand-500/40 bg-slate-900/80' : 'hover:border-slate-700'"
        >
          <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
            @click="toggle(idx)"
          >
            <span class="text-base font-semibold text-white">{{ item.q }}</span>
            <ChevronDown
              class="h-5 w-5 flex-shrink-0 text-slate-400 transition-transform duration-300"
              :class="openIdx === idx ? 'rotate-180 text-brand-400' : ''"
            />
          </button>
          <div
            class="grid transition-all duration-300"
            :class="openIdx === idx ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
          >
            <div class="overflow-hidden">
              <p class="px-6 pb-5 text-sm leading-relaxed text-slate-400">{{ item.a }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
