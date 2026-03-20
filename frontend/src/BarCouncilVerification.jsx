import React from 'react';
import { HiScale, HiCheck, HiFingerPrint, HiArrowRight } from 'react-icons/hi2';
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram } from 'react-icons/fa';
import { Link } from 'react-router-dom'
import { List } from 'lucide-react';
import Header from './Header'


export default function BarCouncilVerification() {
 
  const texta  = {
    textDecoration: 'none'
  }
 
   const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' })


  return (
    <div className="min-h-screen bg-[#f5f5f5] flex flex-col">
      <Header />
      <div className="flex-1 flex items-center justify-center px-4 py-12">
        <div className="w-full max-w-[680px]">
          <div className="flex justify-center mb-8">
            <div className="relative">
              <div style={{boxShadow: '0 0 12px rgba(188, 145, 60, 0.8)'}} className="w-30 h-30 rounded-full border-2 border-[#4a5568] bg-white flex items-center justify-center">
                <HiScale className="w-15 h-15 text-[#2d3748]" />
              </div>
              <div className="absolute -bottom-1 -right-1 w-8 h-8 bg-[#1e293b] rounded-md flex items-center justify-center">
                <HiCheck className="w-5 h-5 text-white" />
              </div>
            </div>
          </div>

          <div className="text-center mb-2">
            <div className="flex items-center justify-center gap-2 mb-4">
              <span className="w-2 h-2 rotate-45 border border-[#6b7280]"></span>
              {/* <span className="text-[#6b7280] text-xs font-medium tracking-[0.15em] uppercase">Official App</span> */}
              <span className="w-2 h-2 rotate-45 border border-[#6b7280]"></span>
            </div>
            <h1 className="text-[44px] font-serif leading-tight text-[#1a1a1a] mb-8">
             Bar Council of Tamilnadu and Puducherry  <span style={{ color:'#a6833c' }} className="italic font-serif">--Election 2026</span>
            </h1>
            <div className="flex justify-center mb-8">
              <div className="w-12 h-px bg-[#d1d5db] relative">
                <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full border border-[#d1d5db] bg-[#f5f5f5]"></div>
              </div>
            </div>
          </div>

          <div className="text-center mb-12">
            <p className="text-[#4b5563] text-sm font-medium tracking-wide uppercase mb-2">Official verification portal for the Bar Council of <span style={{ color:'#a6833c' }} className="italic font-serif">Tamil Nadu & Puducherry</span>  Election 2026</p>
            {/* <p className="text-[#1a1a1a] text-[22px] font-serif">ELECTION 2026</p> */}
          </div>

          <div className="flex justify-center mb-12">
           <Link to="/verification" style={texta}>  
            <button style={{  }} onClick={scrollToTop} className="bg-[#1e293b] hover:bg-[#334155] text-white px-8 py-4 rounded-lg font-medium text-base flex items-center gap-3 transition-colors w-full max-w-[320px] justify-center">
              Continue
              <HiArrowRight className="w-5 h-5" />
            </button>
            </Link>
          </div>

          <div className="flex items-center justify-center gap-2 text-[#6b7280] text-xs">
            <HiFingerPrint className="w-4 h-4" />
            <span className="uppercase tracking-wide font-medium">Secure • Encrypted • Verified</span>
          </div>
        </div>
      </div>

      {/* <footer className="bg-white border-t border-[#e5e7eb] mt-auto">
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

        <div className="bg-[#fafafa] border-t border-[#e5e7eb] py-3 px-4">
          <div className="flex items-center gap-2 text-[#9ca3af] text-xs">
            <span>Made with</span>
            <svg className="w-12 h-3" viewBox="0 0 48 12" fill="none">
              <text x="0" y="10" fontSize="10" fontWeight="600" fill="#8b5cf6">Visily</text>
            </svg>
          </div>
        </div>
      </footer> */}
      {/* <Footer/> */}
    </div>
  );
}