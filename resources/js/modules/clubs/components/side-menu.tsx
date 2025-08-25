import type { Club } from '~/modules/core/types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import { Separator } from '~/modules/core/components/ui/separator'
import { useAuth } from '~/modules/core/hooks/useAuth'

interface SideMenuProps {
  club: Club
}

export function SideMenu({ club }: SideMenuProps) {
  const { isAdmin } = useAuth()

  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">Menu do Clube</CardTitle>
      </CardHeader>
      <CardContent className="space-y-6">
        <div>
          <h3 className="text-sm font-semibold mb-3 text-muted-foreground uppercase tracking-wide">
            Formações
          </h3>
          <ul className="space-y-2">
            <li>
              <Link
                href={route('edit_lineup', { club: club.slug, squad: 'senior' })}
                className="block w-full text-left px-3 py-2 text-sm text-foreground hover:bg-accent hover:text-accent-foreground rounded-md transition-colors"
              >
                Time Principal
              </Link>
            </li>

            <li>
              <Link
                href={route('edit_lineup', { club: club.slug, squad: 'u21' })}
                className="block w-full text-left px-3 py-2 text-sm text-foreground hover:bg-accent hover:text-accent-foreground rounded-md transition-colors"
              >
                Reserva / U21
              </Link>
            </li>

            <li>
              <Link
                href={route('edit_lineup', { club: club.slug, squad: 'u19' })}
                className="block w-full text-left px-3 py-2 text-sm text-foreground hover:bg-accent hover:text-accent-foreground rounded-md transition-colors"
              >
                U19
              </Link>
            </li>
          </ul>
        </div>

        {isAdmin && (
          <>
            <Separator />
            <div>
              <Link
                href={route('club.edit', { club: club.slug })}
                className="block w-full text-left px-3 py-2 text-sm text-destructive hover:bg-destructive/10 rounded-md transition-colors"
              >
                Editar Clube
              </Link>
            </div>
          </>
        )}
      </CardContent>
    </Card>
  )
}
