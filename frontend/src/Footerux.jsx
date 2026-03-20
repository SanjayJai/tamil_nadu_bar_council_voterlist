import React from 'react'
import { HiScale } from 'react-icons/hi2'
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram } from 'react-icons/fa'
import { Link } from 'react-router-dom'


export default function Footerux() {
  const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' })

  return (
    <>
      <footer className="bg-white border-t border-[#e5e7eb] mt-auto">
        <div className="max-w-7xl mx-auto px-4 py-8 sm:px-8 sm:py-12">
          
          {/* Top Section (desktop only) */}
          <div className="hidden sm:grid sm:grid-cols-1 md:grid-cols-4 gap-6 sm:gap-12 mb-6 sm:mb-12">
            
            {/* Brand */}
            <div>
              <div className="flex items-center gap-2 mb-4">
                <div className="w-8 h-8 bg-[#1e293b] rounded flex items-center justify-center">
                  <HiScale className="w-5 h-5 text-white" />
                </div>
                <span className="text-[#1a1a1a] font-semibold text-base">
                  BCTNPY
                </span>
              </div>

              <p className="text-[#6b7280] text-sm leading-relaxed mb-6">
                Streamlining voter verification and election information for the Bar Council.
              </p>

              <div className="hidden sm:flex items-center gap-4">
                <a href="https://www.facebook.com/redback.in" target='_blank' className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaFacebookF className="w-5 h-5" />
                </a>
                <a href="https://x.com/redbackindia" target='_blank' className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaTwitter className="w-5 h-5" />
                </a>
                <a href="https://www.linkedin.com/company/redback-it-solutions-pvt-ltd/" target='_blank' className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaLinkedinIn className="w-5 h-5" />
                </a>
                <a href="https://www.instagram.com/redbacksolutions/" target='_blank' className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaInstagram className="w-5 h-5" />
                </a>
              </div>
            </div>

            {/* Quick Links */}
            <div className="hidden sm:block">
              <h3 className="text-[#1a1a1a] font-semibold text-sm mb-4">
                Quick Links
              </h3>
              <ul className="space-y-3">
                <li>
                  <Link to="/" onClick={scrollToTop} className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">
                    Home
                  </Link>
                </li>
                <li>
                  <Link to="/verification" onClick={scrollToTop} className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">
                    Verification
                  </Link>
                </li>
                <li>
                  <a
                    href="https://redback.in/contact"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors"
                  >
                    Contact
                  </a>
                </li>
              </ul>
            </div>

            {/* Spacer column (optional for layout balance) */}
            <div></div>

            {/* Legal */}
            <div className="hidden sm:block">
              <h3 className="text-[#1a1a1a] font-semibold text-sm mb-4">
                Legal
              </h3>
              <ul className="space-y-3">
                <li>
                  <Link to="/privacy-policy" onClick={scrollToTop} className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">
                    Privacy Policy
                  </Link>
                </li>
                <li>
                  <Link to="/terms-service" onClick={scrollToTop} className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">
                    Terms of Service
                  </Link>
                </li>
               
              </ul>
            </div>
          </div>

          {/* Bottom Section */}
          <div className="pt-6 sm:pt-8 border-t border-[#e5e7eb]">
            <div className="flex flex-col sm:flex-row justify-center items-center gap-2 text-[#6b7280] text-sm">
              <span>
                © 2026 Bar Council Verification. All rights reserved.
              </span>
              <a
                href="https://redbackstudios.in/"
                target="_blank"
                rel="noopener noreferrer"
                className="text-[#a6833c] font-semibold hover:underline"
              >
                REDBACK
              </a>
            </div>
          </div>

        </div>
      </footer>
    </>
  )
}
