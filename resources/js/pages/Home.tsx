import { Head, Link } from '@inertiajs/react'
import { route } from 'ziggy-js'

import Layout from '@/Layouts/Layout'

type Club = {
  id: string
  name: string
  locale: string
  colors: string[]
}

type HomeProps = {
  clubs: Club[]
  auth: any
}

export default function Home({ auth, clubs }: HomeProps) {
  return (
    <Layout

    // auth={auth}
    // errors={errors}
    // header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Home</h2>}
    >
      <Head title="Home" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="p-6 bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm sm:rounded-lg">
            <h2 className="text-lg mb-4 dark:text-slate-100">List of available clubs</h2>

            <div className="grid grid-cols-4 gap-4">
              {clubs.map((club) => (
                <div className="flex gap-1 items-center" key={club.id}>
                  <div
                    className="w-5 h-5 aspect-square rounded-full border-solid border-2"
                    style={{
                      backgroundColor: club.colors?.[0],
                      borderColor: club.colors?.[1],
                    }}
                  />

                  <img
                    className="w-3"
                    src={`/images/vendor/flag-icon-css/flags/4x3/${club.locale.toLowerCase()}.svg`}
                    title={club.locale}
                    alt={`${club.locale} flag`}
                  />

                  <a href={route('club.show', [club])} className="dark:text-slate-100">
                    {club.name}
                  </a>

                  {auth.user && !auth.user.club ? (
                    <Link
                      href={route('apply_for_job', [club])}
                      className="text-gray-500 ml-auto underline text-sm"
                    >
                      Apply for job
                    </Link>
                  ) : (
                    <span className="ml-auto">No manager</span>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </Layout>
  )
}
