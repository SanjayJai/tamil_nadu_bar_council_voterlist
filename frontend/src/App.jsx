import './App.css'
import Welcome from './Welcome'
import BarCouncilVerification from './BarCouncilVerification'
import Footer from './Footerux'
import PrivacyPolicy from './PrivacyPolicy'
import TermsService from './TermsService'
import { BrowserRouter, Routes, Route } from 'react-router-dom'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Home Page */}
        <Route path="/verification" element={<Welcome />} />

    
        <Route path="/" element={<BarCouncilVerification />} />
        <Route path="/privacy-policy" element={<PrivacyPolicy />} />
        <Route path="/terms-service" element={<TermsService />} />
      </Routes>

     
      <Footer />
    </BrowserRouter>
  )
}

export default App
