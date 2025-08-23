import type { SharedData } from '~/types/shared'

import { useEffect } from 'react'
import { usePage } from '@inertiajs/react'
import { toast } from 'sonner'

import { Toaster } from './ui/sonner'

export function Flash() {
  const {
    props: { flash },
  } = usePage<SharedData>()

  useEffect(() => {
    if (flash?.message) {
      toast[flash.type](flash.message)
    }
  }, [flash])

  return <Toaster />
}
