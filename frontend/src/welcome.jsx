import React from 'react';
import { HiScale, HiArrowRight, HiUser, HiCreditCard, HiXMark, HiCheck } from 'react-icons/hi2';
import { FaFacebookF, FaTwitter, FaLinkedinIn, FaInstagram } from 'react-icons/fa';
import { useState, useRef, useEffect } from 'react';

import { Link } from 'react-router-dom'
import Header from './Header'

export default function Welcome() {

     const texta  = {
    textDecoration: 'none'
  }

    const API_BASE = import.meta.env.VITE_API_BASE || 'http://localhost:8000';

    const [mode, setMode] = useState('name');
    const [query, setQuery] = useState('');
    const [loading, setLoading] = useState(false);
    const [result, setResult] = useState(null);
    const [error, setError] = useState(null);
    const [suggestions, setSuggestions] = useState([]);

    const debounceTimer = useRef(null);
    const lastSuggestQuery = useRef('');
    const highlighted = useRef(-1);
    const [suggestPage, setSuggestPage] = useState(1);
    const [suggestLoading, setSuggestLoading] = useState(false);
    const [suggestHasMore, setSuggestHasMore] = useState(true);

    const inputRef = useRef(null);
    const dropdownRef = useRef(null);

    async function search(e) {
        e.preventDefault();
        setLoading(true);
        setError(null);
        setResult(null);

        try {
            const res = await fetch(`${API_BASE}/api/advocate/search`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ mode, query }),
            });

            const json = await res.json();
                if (!res.ok || json.success === false) {
                setError(json.message || 'Not found');
            } else {
                setResult(json.data);
                // close mobile menu when a result opens
                try { window.dispatchEvent(new Event('closeMobileMenu')) } catch (e) {}
                // blur input to close on-screen keyboard on mobile
                try { inputRef.current?.blur(); } catch (e) {}
                try { document.activeElement?.blur?.(); } catch (e) {}
            }
        } catch {
            setError('Request failed');
        } finally {
            setLoading(false);
        }
    }

    async function suggest(nameQuery, page = 1) {
        const q = nameQuery.trim();
        if (q.length < 2) {
            setSuggestions([]);
            setSuggestHasMore(false);
            return;
        }
        lastSuggestQuery.current = q;
        const per_page = 20;
        try {
            if (page === 1) {
                setSuggestLoading(true);
                setSuggestHasMore(true);
            } else {
                setSuggestLoading(true);
            }

            const res = await fetch(`${API_BASE}/api/advocate/search`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ mode: 'name', query: q, suggest: true, page, per_page }),
            });

            const json = await res.json();
            if (lastSuggestQuery.current !== q) return;

            const items = (json.suggestions || []);
            if (page === 1) {
                setSuggestions(items);
            } else {
                setSuggestions((prev) => [...prev, ...items]);
            }

            setSuggestPage(page);
            setSuggestHasMore(items.length === per_page);
        } catch {
            if (page === 1) setSuggestions([]);
            setSuggestHasMore(false);
        } finally {
            setSuggestLoading(false);
        }
    }

    useEffect(() => {
        function handleClickOutside(event) {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target) && 
                inputRef.current && !inputRef.current.contains(event.target)) {
                setSuggestions([]);
            }
        }

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    useEffect(() => {
        if (result) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
        return () => {
            document.body.style.overflow = 'unset';
        };
    }, [result]);

    useEffect(() => {
        document.title = 'Tamil Nadu & Puducherry Bar Council Election 2026';
    }, []);

    const closeModal = () => {
        setResult(null);
        setQuery('');
        setError(null);
        setSuggestions([]);
    };

    return (
        <div className="min-h-screen bg-[#f5f5f5] flex flex-col">
            <Header />
   <div className="flex justify-center mb-8 mt-12">
            <div className="relative">
              <div style={{boxShadow: '0 0 12px rgba(188, 145, 60, 0.8)'}} className="w-20 h-20 rounded-full border-2 border-[#4a5568] bg-white flex items-center justify-center">
                <HiScale className="w-10 h-10 text-[#2d3748]" />
              </div>
              
            </div>
          </div>

<h1     className="text-4xl sm:text-5xl font-serif text-[#1a1a1a] text-center mt-12">
                        Bar Council of <span style={{ color: '#d09e3b' }} className="text-white/80 italic">Tamil Nadu &amp; Puducherry</span><br/>Election 2026
</h1>
            <main  className="flex-1 flex items-center justify-center px-4 py-8 sm:py-12">
                <div style={{borderTop: '5px solid #d09e3b'  }} className="w-full max-w-[420px] bg-white rounded-2xl shadow-sm p-8 sm:p-12">
                    {/* <h1 style={{ fontSize:'2rem' }} className="font-serif text-[34px] leading-none whitespace-nowrap mb-6 text-[#1a1a1a]">
  Bar Council <span style={{ color:'#d09e3b' }} className="italic text-[#a6833c]">Verification</span>
</h1> */}

                    <p style={{ textAlign: 'center' }} className="text-[#1a1a1a] text-[22px] font-serif">Official Verification Portal</p>
                    <br></br>
                    <br></br>

                    <form onSubmit={search} className="space-y-4 sm:space-y-6">
                        <div className="relative">
                            <input
                                ref={inputRef}
                                type="text"
                                placeholder={mode === 'name' ? 'e.g., Sabari' : 'e.g. Ms/123/2005'}
                                value={query}
                                onChange={(e) => {
                                    const v = e.target.value;
                                    setQuery(v);
                                    setResult(null);
                                    highlighted.current = -1;
                                    if (debounceTimer.current) clearTimeout(debounceTimer.current);
                                    if (mode === 'name') {
                                        debounceTimer.current = setTimeout(() => suggest(v), 150);
                                    } else {
                                        setSuggestions([]);
                                    }
                                }}
                                onKeyDown={(e) => {
                                    if (suggestions.length === 0) return;
                                    if (e.key === 'ArrowDown') {
                                        e.preventDefault();
                                        highlighted.current = Math.min(highlighted.current + 1, suggestions.length - 1);
                                        setSuggestions([...suggestions]);
                                    } else if (e.key === 'ArrowUp') {
                                        e.preventDefault();
                                        highlighted.current = Math.max(highlighted.current - 1, 0);
                                        setSuggestions([...suggestions]);
                                    } else if (e.key === 'Enter') {
                                        if (highlighted.current >= 0 && highlighted.current < suggestions.length) {
                                            e.preventDefault();
                                            const s = suggestions[highlighted.current];
                                            setQuery(s.name);
                                            setSuggestions([]);
                                            setResult(s);
                                        }
                                    } else if (e.key === 'Escape') {
                                        setSuggestions([]);
                                    }
                                }}
                                className="w-full px-4 py-3 border border-[#d1d5db] rounded-lg text-sm text-[#1a1a1a] placeholder:text-[#9ca3af] focus:outline-none focus:border-[#1e293b] focus:ring-1 focus:ring-[#1e293b]"
                            />
                            {(suggestLoading || loading) ? (
                                <svg className="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#6b7280] animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            ) : (
                                <HiUser className="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#6b7280]" />
                            )}

                            {mode === 'name' && suggestions.length > 0 && (
                                <div 
                                    ref={dropdownRef}
                                    className="absolute left-0 right-0 mt-2 bg-white rounded-lg overflow-hidden z-50 border border-[#d1d5db] shadow-xl max-h-[280px] overflow-y-auto"
                                >
                                    <div className="px-4 py-3 sticky top-0 bg-white border-b border-[#e5e7eb] text-xs text-[#6b7280] font-semibold select-none">
                                        <div className="grid grid-cols-[2fr_1fr_1fr] gap-2 sm:gap-4 items-center">
                                            <div>NAME</div>
                                            <div className="text-center hidden sm:block">ENROLLMENT</div>
                                            <div className="text-center hidden sm:block">YEAR</div>
                                        </div>
                                    </div>
                                    <div
                                        onScroll={(e) => {
                                            const el = e.currentTarget;
                                            if (!el) return;
                                            const nearBottom = el.scrollTop + el.clientHeight >= el.scrollHeight - 80;
                                            if (nearBottom && suggestHasMore && !suggestLoading) {
                                                suggest(lastSuggestQuery.current, suggestPage + 1);
                                            }
                                        }}
                                    >
                                        {suggestions.map((s, idx) => {
                                            const enoStr = s.enrollment_no_str ?? '';
                                            const enoNum = s.enrollment_no ?? '';
                                            let enoPart = enoStr || enoNum || '';
                                            let yearPart = s.year ?? '';
                                            if (!yearPart && typeof enoPart === 'string' && enoPart.includes('/')) {
                                                const parts = enoPart.split('/').map((p) => p.trim());
                                                enoPart = parts[0] ?? enoPart;
                                                yearPart = parts[1] ?? '';
                                            }

                                            return (
                                                <div
                                                    key={s.id}
                                                    className={`px-4 py-3 hover:bg-[#f5f5f5] cursor-pointer border-b border-[#e5e7eb] transition-colors ${highlighted.current === idx ? 'bg-[#f5f5f5]' : ''}`}
                                                    onMouseEnter={() => { highlighted.current = idx; setSuggestions([...suggestions]); }}
                                                    onMouseDown={(e) => {
                                                        e.preventDefault();
                                                        if (debounceTimer.current) clearTimeout(debounceTimer.current);
                                                        setQuery(s.name);
                                                        setSuggestions([]);
                                                        setResult(s);
                                                        setError(null);
                                                        try { window.dispatchEvent(new Event('closeMobileMenu')) } catch (e) {}
                                                        try { inputRef.current?.blur(); } catch (e) {}
                                                        try { document.activeElement?.blur?.(); } catch (e) {}
                                                    }}
                                                >
                                                    <div className="grid grid-cols-1 sm:grid-cols-[2fr_1fr_1fr] gap-1 sm:gap-4 items-center">
                                                        <div>
                                                            <div className="font-semibold text-[#1a1a1a] text-sm">
                                                                {(s.name ?? '').toUpperCase()}
                                                            </div>
                                                            <div className="sm:hidden text-xs text-[#6b7280] mt-1">
                                                                {enoPart} {yearPart && `/ ${yearPart}`}
                                                            </div>
                                                        </div>
                                                        <div className="text-center text-[#6b7280] text-sm font-medium hidden sm:block">
                                                            {enoPart}
                                                        </div>
                                                        <div className="text-center text-[#1a1a1a] text-sm font-medium hidden sm:block">
                                                            {yearPart}
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                        {suggestLoading && (
                                            <div className="px-4 py-3 text-sm text-[#6b7280] text-center">Loading more…</div>
                                        )}
                                        {!suggestHasMore && suggestions.length > 0 && (
                                            <div className="px-4 py-3 text-sm text-[#6b7280] text-center">End of results</div>
                                        )}
                                    </div>
                                </div>
                            )}
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="flex-1 h-px bg-[#e5e7eb]"></div>
                            <HiCreditCard className="w-5 h-5 text-[#6b7280]" />
                            <div className="flex-1 h-px bg-[#e5e7eb]"></div>
                        </div>

                        <div className="flex gap-3">
                            <button
                                type="button"
                                className={`flex-1 py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-colors ${
                                    mode === 'name'
                                        ? 'bg-[#1e293b] text-white'
                                        : 'border border-[#d1d5db] text-[#6b7280] hover:border-[#1e293b]'
                                }`}
                                onClick={() => {
                                    setMode('name');
                                    setQuery('');
                                    setSuggestions([]);
                                    setResult(null);
                                    setError(null);
                                    inputRef.current?.focus();
                                }}
                            >
                                Search by Name
                            </button>

                            <button
                                type="button"
                                className={`flex-1 py-2.5 rounded-lg text-xs sm:text-sm font-medium transition-colors ${
                                    mode === 'enrollment'
                                        ? 'bg-[#1e293b] text-white'
                                        : 'border border-[#d1d5db] text-[#6b7280] hover:border-[#1e293b]'
                                }`}
                                onClick={() => {
                                    setMode('enrollment');
                                    setQuery('');
                                    setSuggestions([]);
                                    setResult(null);
                                    setError(null);
                                    inputRef.current?.focus();
                                }}
                            >
                                Enrollment No
                            </button>
                        </div>

                        <button 
                            type="submit"
                            disabled={loading || query.trim() === ''}
                            className="w-full bg-[#1e293b] hover:bg-[#334155] text-white py-3 sm:py-3.5 rounded-lg font-medium text-sm sm:text-base flex items-center justify-center gap-3 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Searching…' : 'Check Status'}
                            <HiArrowRight className="w-5 h-5" />
                        </button>

                        {error && (
                            <div className="text-center text-red-500 text-sm mt-4">{error}</div>
                        )}
                    </form>

                    <div className="text-center mt-6">
                        <a href="#" className="text-[#6b7280] text-xs sm:text-sm hover:text-[#1a1a1a] underline">
                            Need help locating your number?
                        </a>
                    </div>

                    <div className="flex items-center justify-center gap-2 text-[#9ca3af] text-xs mt-6">
                        <span className="w-1 h-1 bg-[#9ca3af] rounded-full"></span>
                        <span className="uppercase tracking-wide font-medium">Secure Gateway</span>
                        <span className="w-1 h-1 bg-[#9ca3af] rounded-full"></span>
                    </div>
                </div>
            </main>

            {result && (
                <div 
                    className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-60 overflow-y-auto"
                    onClick={(e) => {
                        if (e.target === e.currentTarget) {
                            closeModal();
                        }
                    }}
                >
                    <div className="bg-white rounded-3xl shadow-2xl w-full max-w-md border-2 border-[#e5e7eb] relative my-8">
                        <button
                            onClick={closeModal}
                            className="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full hover:bg-[#f5f5f5] transition-colors z-10"
                        >
                            <HiXMark className="w-6 h-6 text-[#6b7280]" />
                        </button>

                        <div className="p-8 sm:p-10 text-center">
                            <div className="flex justify-center mb-6">
                                <div className="relative">
                                    <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-[#d1d5db] bg-white flex items-center justify-center">
                                        <svg className="w-10 h-10 sm:w-12 sm:h-12 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div className="absolute -bottom-1 -right-1 w-6 h-6 sm:w-8 sm:h-8 bg-[#fbbf24] rounded-full flex items-center justify-center">
                                        <span className="text-white text-base sm:text-lg">✨</span>
                                    </div>
                                </div>
                            </div>

                            <h1 className="text-3xl sm:text-4xl font-serif text-[#1a1a1a] mb-2">Eligibility Confirmed</h1>
                            <p className="text-xs sm:text-sm text-[#6b7280] uppercase tracking-wide mb-8">Election 2026</p>

                            <div className="w-16 h-px bg-[#d1d5db] mx-auto mb-8"></div>

                            <div className="space-y-6 text-left">
                                <div>
                                    <div className="text-xs text-[#6b7280] uppercase tracking-wide mb-1">Voter Name</div>
                                    <div className="text-xl sm:text-2xl font-serif text-[#1a1a1a]">
                                        {(result.name ?? '').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ')}
                                    </div>
                                </div>

                                <div className="w-full h-px bg-[#d1d5db]"></div>

                                <div>
                                    <div className="text-xs text-[#6b7280] uppercase tracking-wide mb-1">Enrollment No.</div>
                                    <div className="text-lg sm:text-xl font-serif text-[#1a1a1a]">
                                        {(() => {
                                            const enoStr = result.enrollment_no_str ?? '';
                                            const enoNum = result.enrollment_no ?? '';
                                            const year = result.year ?? '';

                                            if (enoStr) {
                                                return year ? `${enoStr}` : enoStr;
                                            }

                                            if (enoNum) {
                                                return year ? `${enoNum}` : enoNum;
                                            }

                                            return '';
                                        })()}
                                    </div>
                                </div>

                                <div className="w-full h-px bg-[#d1d5db]"></div>

                                <div>
                                    <div className="text-xs text-[#6b7280] uppercase tracking-wide mb-1">Polling Station</div>
                                    <div className="text-base sm:text-lg text-[#1a1a1a]">
                                        {result.bar_association || 'High Court Campus, Chennai'}
                                    </div>
                                </div>
                            </div>
                            
                           <Link to="/"  style={texta} >
                            <button 
                                onClick={() => {
                                    console.log('Thank you for verifying');
                                }}
                                className="w-full mt-8 bg-[#1a1a1a] hover:bg-[#2d3748] text-white py-3 sm:py-4 rounded-xl font-medium text-sm sm:text-base flex items-center justify-center gap-3 transition-colors"
                            >
                                Thank You for Verifying
                                <HiArrowRight className="w-5 h-5" />
                            </button>
                            </Link>

                            <p className="text-[10px] sm:text-xs text-[#6b7280] mt-6">
                                Issued by the Tamil Nadu & Puducherry Bar Council
                            </p>
                        </div>
                    </div>
                </div>
            )}

        </div>
    );
}