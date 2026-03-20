import React from 'react'
import { HiScale } from 'react-icons/hi2';
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram } from 'react-icons/fa';



export default function Footerux() {
  return (

    <>
     <footer className="bg-white border-t border-[#e5e7eb] mt-auto">
        <div className="max-w-7xl mx-auto px-8 py-12">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div>
              <div className="flex items-center gap-2 mb-4">
                <div className="w-8 h-8 bg-[#1e293b] rounded flex items-center justify-center">
                  <HiScale className="w-5 h-5 text-white" />
                </div>
                <span className="text-[#1a1a1a] font-semibold text-base">Bar Council Verification</span>
              </div>
              <p className="text-[#6b7280] text-sm leading-relaxed mb-6">
                Streamlining voter verification and election information for the Bar Council.
              </p>
              <div className="flex items-center gap-4">
                <a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaFacebookF className="w-5 h-5" />
                </a>
                <a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaTwitter className="w-5 h-5" />
                </a>
                <a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaLinkedinIn className="w-5 h-5" />
                </a>
                <a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] transition-colors">
                  <FaInstagram className="w-5 h-5" />
                </a>
              </div>
            </div>

            <div>
              <h3 className="text-[#1a1a1a] font-semibold text-sm mb-4">Quick Links</h3>
              <ul className="space-y-3">
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Home</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Verification</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">About Us</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Contact</a></li>
              </ul>
            </div>

            <div>
              <h3 className="text-[#1a1a1a] font-semibold text-sm mb-4">Legal</h3>
              <ul className="space-y-3">
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Privacy Policy</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Terms of Service</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Disclaimer</a></li>
              </ul>
            </div>

            <div>
              <h3 className="text-[#1a1a1a] font-semibold text-sm mb-4">Support</h3>
              <ul className="space-y-3">
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">FAQ</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Help Center</a></li>
                <li><a href="#" className="text-[#6b7280] hover:text-[#1a1a1a] text-sm transition-colors">Report an Issue</a></li>
              </ul>
            </div>
          </div>

          <div className="pt-8 border-t border-[#e5e7eb]">
            <p className="text-center text-[#6b7280] text-sm">
              © 2026 Bar Council Verification. All rights reserved.
            </p>
          </div>
        </div>

      
      </footer>
    </>
 

  )
}
