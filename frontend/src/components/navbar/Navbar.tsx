import React from 'react'
import { Box, Flex, IconButton, Spacer, Text } from '@chakra-ui/react'
import { useState } from 'react'
import {
  FaSun,
  FaMoon,
  FaFacebook,
  FaInstagram,
  FaSoundcloud,
} from 'react-icons/fa'

function Logo() {
  return (
    <Box>
      <Text fontSize="lg" fontWeight="bold">
        Logo
      </Text>
    </Box>
  )
}
export default function Navbar() {
  const [darkMode, setDarkMode] = useState<boolean>(false)

  return (
    <Flex
      as="nav"
      w="100%"
      align="center"
      justify="space-between"
      ml={6}
      mr={6}
    >
      <Logo />
      <Spacer />
      <Box>
        <IconButton
          aria-label={'Facebook'}
          icon={<FaFacebook />}
          isRound={true}
          onClick={() => window.open('')}
        />
        <IconButton
          aria-label={'Instagram'}
          icon={<FaInstagram />}
          isRound={true}
          onClick={() => window.open('')}
        />
        <IconButton
          aria-label={'SoundCloud'}
          icon={<FaSoundcloud />}
          isRound={true}
          onClick={() => window.open('')}
        />
        <IconButton
          aria-label={'Dark mode'}
          icon={darkMode ? <FaSun /> : <FaMoon />}
          isRound={true}
          onClick={() => setDarkMode(!darkMode)}
        />
      </Box>
    </Flex>
  )
}
