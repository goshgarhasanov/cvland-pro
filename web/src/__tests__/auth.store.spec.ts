import { describe, expect, it } from 'vitest'

import { useAuthStore } from '@/stores/auth'

describe('auth store', () => {
  it('starts unauthenticated', () => {
    const auth = useAuthStore()
    expect(auth.isAuthenticated).toBe(false)
    expect(auth.user).toBe(null)
  })
})
