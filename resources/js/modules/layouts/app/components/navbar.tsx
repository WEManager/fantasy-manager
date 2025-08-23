import type * as React from 'react'

import { usePage } from '@inertiajs/react'
import { Menu } from 'lucide-react'

import { Button } from '~/modules/core/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '~/modules/core/components/ui/dropdown-menu'
import { Separator } from '~/modules/core/components/ui/separator'
import { Sheet, SheetContent, SheetTrigger } from '~/modules/core/components/ui/sheet'
import { cn } from '~/modules/core/utils/class-value'
import { ThemeToggle } from '~/modules/layouts/app/components/theme-toggle'

type Club = { id: string | number; name: string }
type User = { name: string; club?: Club }

type NavbarProps = {
  className?: string
}

function A({ href, className, children, ...rest }: React.ComponentProps<'a'>) {
  return (
    <a href={href} className={className} {...rest}>
      {children}
    </a>
  )
}

export function Navbar({ className }: NavbarProps) {
  const { auth, ziggy } = usePage<{
    auth: { user: User | null }
    ziggy: { location: string }
  }>().props

  const isAuthenticated = !!auth.user
  const user = auth.user

  const isActive = (routeName?: string) => {
    if (!routeName) return 'text-foreground'

    // Compara se a rota atual corresponde ao nome da rota
    const currentRoute = route().current()
    return currentRoute === routeName ? 'text-foreground' : 'text-muted-foreground'
  }

  const club = user?.club
  const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

  return (
    <nav className={cn('border-b bg-background', className)}>
      <div className="container mx-auto flex h-16 items-center justify-between px-4">
        {/* Brand */}
        <A href={route('home')} className="font-semibold" aria-label={appName}>
          {appName}
        </A>

        {/* Desktop right side */}
        <div className="hidden items-center gap-2 md:flex">
          <ThemeToggle />

          {!isAuthenticated ? (
            <>
              <A href={route('login')} className={cn('text-sm px-3 py-2', isActive('login'))}>
                Login
              </A>
              <A href={route('register')} className={cn('text-sm px-3 py-2', isActive('register'))}>
                Register
              </A>
            </>
          ) : (
            <>
              <Button asChild variant="ghost" className="text-sm">
                <a href="https://discord.gg/tzFVp2y" target="_blank" rel="noreferrer">
                  Join us on Discord
                </a>
              </Button>

              {/* Club dropdown (se existir) */}
              {club && (
                <DropdownMenu>
                  <DropdownMenuTrigger asChild>
                    <Button variant="ghost" className="text-sm">
                      {club.name}
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end" className="w-56">
                    <DropdownMenuLabel>Club</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem asChild>
                      <A href={route('club.show', { club })} className="w-full">
                        Clubhouse
                      </A>
                    </DropdownMenuItem>
                    <DropdownMenuItem asChild>
                      <A href={route('show_club_players', { club })} className="w-full">
                        Players
                      </A>
                    </DropdownMenuItem>
                    <DropdownMenuItem asChild>
                      <A href={route('quit_job', { club })} className="w-full">
                        Quit Job
                      </A>
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              )}

              {/* User dropdown */}
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" className="text-sm">
                    {user?.name ?? 'Account'}
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-56">
                  <DropdownMenuItem asChild>
                    <form action={route('logout')} method="POST" className="w-full">
                      {/* ajuste conforme seu framework: Inertia, Next API, etc. */}
                      <button type="submit" className="w-full text-left">
                        Logout
                      </button>
                    </form>
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </>
          )}
        </div>

        {/* Mobile menu */}
        <Sheet>
          <SheetTrigger asChild>
            <Button size="icon" variant="ghost" className="md:hidden">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Open menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="right" className="w-80">
            <div className="flex flex-col gap-2">
              <A href={route('home')} className="font-semibold text-base">
                {appName}
              </A>
              <Separator className="my-2" />

              <div className="flex items-center justify-between">
                <span className="text-sm text-muted-foreground">Theme</span>
                <ThemeToggle />
              </div>

              {!isAuthenticated ? (
                <>
                  <A href={route('login')} className={cn('py-2', isActive('login'))}>
                    Login
                  </A>
                  <A href={route('register')} className={cn('py-2', isActive('register'))}>
                    Register
                  </A>
                </>
              ) : (
                <>
                  <a
                    href="https://discord.gg/tzFVp2y"
                    target="_blank"
                    rel="noreferrer"
                    className="py-2"
                  >
                    Join us on Discord
                  </a>

                  {club && (
                    <>
                      <Separator className="my-2" />
                      <div className="text-xs uppercase text-muted-foreground">Club</div>
                      <A href={route('club.show', { club })} className="py-2">
                        Clubhouse
                      </A>
                      <A href={route('show_club_players', { club })} className="py-2">
                        Players
                      </A>
                      <A href={route('quit_job', { club })} className="py-2">
                        Quit Job
                      </A>
                    </>
                  )}

                  <Separator className="my-2" />
                  <div className="text-xs uppercase text-muted-foreground">Account</div>
                  <form action={route('logout')} method="POST">
                    <Button type="submit" variant="ghost" className="px-0">
                      Logout
                    </Button>
                  </form>
                </>
              )}
            </div>
          </SheetContent>
        </Sheet>
      </div>
    </nav>
  )
}
