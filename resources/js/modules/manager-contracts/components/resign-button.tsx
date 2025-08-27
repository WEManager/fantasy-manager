import type { Club } from '~/modules/core/types'

import { Link } from '@inertiajs/react'

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '~/modules/core/components/ui/alert-dialog'
import { Button } from '~/modules/core/components/ui/button'

interface ResignButtonProps {
  club: Club
}

export function ResignButton({ club }: ResignButtonProps) {
  return (
    <AlertDialog>
      <AlertDialogTrigger asChild>
        <Button variant="destructive" size="sm">
          Renunciar
        </Button>
      </AlertDialogTrigger>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Confirmar Renúncia</AlertDialogTitle>
          <AlertDialogDescription>
            Tem certeza que deseja renunciar ao cargo de manager do {club.name}? Esta ação não pode
            ser desfeita e pode ter consequências para sua carreira.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>Cancelar</AlertDialogCancel>
          <AlertDialogAction asChild>
            <Link href={route('club.resign', { club: club.slug })}>Confirmar Renúncia</Link>
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  )
}
