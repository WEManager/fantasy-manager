import DefaultLayout from '@/Layouts/Default'
import { Head, Link } from '@inertiajs/inertia-react'
import { Button } from 'flowbite-react'

type ClubIndex = {
  clubs: any
}

export default function ClubIndex({ clubs }: ClubIndex) {
  return (
    <DefaultLayout>
      <Head title="Clubes" />
      {clubs.data.map((club) => (
        <div key={club.id}>
          <Link href={route('club.show', club)}>{club.name}</Link>
        </div>
      ))}

      <div className="flex gap-2 mt-4">
        {clubs.links.map((link) => (
          <Link href={link.url} key={link.label}>
            <Button>{link.label}</Button>
          </Link>
        ))}
      </div>
    </DefaultLayout>
  )
}
