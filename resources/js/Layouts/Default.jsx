import { Nav } from "@/components";

export default function DefaultLayout({ auth, header, children }) {
  return (
    <>
      <Nav />

      <main>{children}</main>
    </>
  )
}
