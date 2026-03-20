import React, { useState } from 'react'
import { useEffect } from 'react'
import { HiScale, HiBars3, HiXMark } from 'react-icons/hi2'
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram } from 'react-icons/fa'
import { Link } from 'react-router-dom'

export default function Header() {
  const [open, setOpen] = useState(false)

  const close = () => setOpen(false)
  const scrollTopAndClose = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
    close()
  }

  useEffect(() => {
    function onCloseMenu() {
      setOpen(false)
    }
    window.addEventListener('closeMobileMenu', onCloseMenu)
    return () => window.removeEventListener('closeMobileMenu', onCloseMenu)
  }, [])

  return (
    <header className="bg-white border-b border-[#e5e7eb] px-4 sm:px-6 py-4 relative z-40">
      <div className="max-w-7xl mx-auto flex items-center justify-between gap-2 sm:gap-3">
        <div className="flex items-center gap-2">
          <div className="w-8 h-8 sm:w-10 sm:h-10 bg-[#1e293b] rounded-lg flex items-center justify-center flex-shrink-0">
            <HiScale className="w-5 h-5 sm:w-6 sm:h-6 text-white" />
          </div>
          <div className="flex items-center gap-2">
            <span className="text-[#1a1a1a] font-semibold text-base sm:text-lg">BCTNPY</span>
            <span className="text-[#6b7280] text-[10px] sm:text-xs"></span>
          </div>
        </div>

        {/* Hamburger for mobile */}
        <button
          onClick={() => setOpen((s) => !s)}
          className={`sm:hidden p-2 rounded-md text-[#1a1a1a] hover:bg-[#f3f4f6] transition-transform duration-200 ${open ? 'rotate-12 scale-105' : ''}`}
          aria-label="Toggle menu"
        >
          {open ? <HiXMark className="w-6 h-6" /> : <HiBars3 className="w-6 h-6" />}
        </button>

        {/* Desktop filler (keeps center alignment) */}
        <div className="hidden sm:block" />

        {/* Mobile right-side sliding panel (always in DOM to allow animation) */}
        <div className={`sm:hidden fixed inset-0 z-50 pointer-events-none ${open ? 'pointer-events-auto' : ''}`} aria-hidden={!open}>
          {/* overlay */}
          <div
            onClick={close}
            className={`absolute inset-0 bg-black/30 transition-opacity duration-300 ${open ? 'opacity-100 pointer-events-auto z-40' : 'opacity-0 pointer-events-none'}`}
          />

          {/* panel */}
          <aside
            className={`absolute top-0 right-0 h-full w-4/5 max-w-sm bg-white p-6 transform transition-transform duration-300 shadow-xl ${open ? 'translate-x-0' : 'translate-x-full'} z-50 pointer-events-auto`}
            role="dialog"
            aria-modal="true"
          >
            <button
              onClick={close}
              aria-label="Close menu"
              className={`absolute top-4 right-4 p-2 rounded-md text-[#1a1a1a] bg-white shadow-sm transition-transform duration-200 ${open ? 'scale-100' : 'scale-90'}`}
            >
              <HiXMark className="w-6 h-6" />
            </button>

            <nav className="mt-8 space-y-4">
              <Link to="/" onClick={scrollTopAndClose} className="block text-lg font-medium text-[#1a1a1a] no-underline">Home</Link>
              <Link to="/verification" onClick={scrollTopAndClose} className="block text-lg font-medium text-[#1a1a1a] no-underline">Verification</Link>
              <Link to="/privacy-policy" onClick={scrollTopAndClose} className="block text-lg font-medium text-[#1a1a1a] no-underline">Privacy Policy</Link>
              <Link to="/terms-service" onClick={scrollTopAndClose} className="block text-lg font-medium text-[#1a1a1a] no-underline">Terms of Service</Link>

              <div className="pt-6 border-t border-[#e5e7eb]">
                <div className="flex items-center gap-4 mt-3">
                  <a href="https://www.facebook.com/redback.in" target="_blank" rel="noreferrer" className="text-[#6b7280] hover:text-[#1a1a1a]"><FaFacebookF className="w-5 h-5" /></a>
                  <a href="https://x.com/redbackindia" target="_blank" rel="noreferrer" className="text-[#6b7280] hover:text-[#1a1a1a]"><FaTwitter className="w-5 h-5" /></a>
                  <a href="https://www.linkedin.com/company/redback-it-solutions-pvt-ltd/" target="_blank" rel="noreferrer" className="text-[#6b7280] hover:text-[#1a1a1a]"><FaLinkedinIn className="w-5 h-5" /></a>
                  <a href="https://www.instagram.com/redbacksolutions/" target="_blank" rel="noreferrer" className="text-[#6b7280] hover:text-[#1a1a1a]"><FaInstagram className="w-5 h-5" /></a>
                </div>
              </div>
            </nav>
          </aside>
        </div>
      </div>
    </header>
  )
}
