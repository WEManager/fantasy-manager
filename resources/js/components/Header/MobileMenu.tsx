import { Link } from '@inertiajs/inertia-react'

import { Icons } from '../Icons'
import { Button } from '../ui/Button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '../ui/Dropdown'
import { ScrollArea } from '../ui/ScrollArea'

export function MobileNav() {
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button
          variant="ghost"
          className="-ml-4 text-base hover:bg-transparent focus:ring-0  focus:ring-offset-0 md:hidden">
          <Icons.logo className="mr-2 h-4 w-4" /> <span className="font-bold">Menu</span>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="start" sideOffset={24} alignOffset={4} className="w-[300px] overflow-scroll">
        <DropdownMenuItem asChild>
          <Link href="/" className="flex items-center">
            <Icons.logo className="mr-2 h-4 w-4" /> WEManager
          </Link>
        </DropdownMenuItem>
        <DropdownMenuSeparator />
        <ScrollArea className="h-[400px]">
          {/* {docsConfig.sidebarNav?.map(
            (item, index) =>
              item.href && (
                <DropdownMenuItem key={index} asChild>
                  <Link href={item.href}>{item.title}</Link>
                </DropdownMenuItem>
              )
          )} */}
          {/* {docsConfig.sidebarNav.map((item, index) => (
            <DropdownMenuGroup key={index}>
              <DropdownMenuSeparator
                className={cn({
                  hidden: index === 0,
                })}
              />
              <DropdownMenuLabel>{item.title}</DropdownMenuLabel>
              <DropdownMenuSeparator className="-mx-2" />
              {item?.items?.length &&
                item.items.map((item) => (
                  <DropdownMenuItem key={item.title} asChild>
                    {item.href ? <Link href={item.href}>{item.title}</Link> : item.title}
                  </DropdownMenuItem>
                ))}
            </DropdownMenuGroup>
          ))} */}
        </ScrollArea>
      </DropdownMenuContent>
    </DropdownMenu>
  )
}
