import { Head, Link } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import { Button } from 'flowbite-react'
import Layout from '@/Layouts/Layout'

type HomeProps = {
  clubs: any[]
  auth: any
}

export default function Home({ auth, clubs }: HomeProps) {
  return (
    <Layout header={<h2 className="font-semibold text-xl text-white leading-tight">Home</h2>}>
      <Head title="Home" />

      <div className="container mx-auto mt-4">
        <section>
          <h2 className="text-lg mb-4">List of available clubs</h2>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {clubs.map((club) => (
              <div
                key={club.id}
                className="rounded border border-gray-200 p-4 flex flex-col items-center"
                style={{ backgroundColor: club.colors[0] }}>
                <h3 className="font-bold" style={{ color: club.colors[1] }}>
                  {club.name}
                </h3>
              </div>
            ))}
          </div>
        </section>
      </div>
    </Layout>
  )
}
