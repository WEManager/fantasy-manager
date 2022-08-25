import Authenticated from "@/Layouts/Authenticated";
import DefaultLayout from "@/Layouts/Default";
import { Head } from "@inertiajs/inertia-react";

export default function ClubIndex({ auth, errors, clubs }) {
  return (
    <DefaultLayout
      auth={auth}
      errors={errors}
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Home</h2>}
    >
      <Head title="Clubes" />

      clubes
    </DefaultLayout>
  )
}