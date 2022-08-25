import DefaultLayout from '@/Layouts/Default'
import { Head, Link } from '@inertiajs/inertia-react'

type HomeProps = {
  clubs: any
}

export default function Home({ auth, clubs }: HomeProps) {
  return (
    <DefaultLayout
      // auth={auth}
      // errors={errors}
      header={
        <h2 className="font-semibold text-xl text-gray-800 leading-tight">
          Home
        </h2>
      }>
      <Head title="Home" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <h2 className="text-lg mb-4">List of available clubs</h2>

              <div className="grid grid-cols-4 gap-4">
                {clubs.map((club) => (
                  <div className="flex gap-1 items-center" key={club.id}>
                    <div
                      className="w-5 aspect-square rounded-full border-solid border-2"
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

                    <a href={route('club.show', [club])}>{club.name}</a>

                    {auth.user && !auth.user.club ? (
                      <Link
                        href={route('apply_for_job', [club])}
                        className="text-gray-500 ml-auto underline text-sm">
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
      </div>
    </DefaultLayout>
  )
}
